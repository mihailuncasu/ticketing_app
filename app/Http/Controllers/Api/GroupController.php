<?php

namespace App\Http\Controllers\Api;

use App\Events\GroupCreatedEvent;
use App\Events\GroupDestroyedEvent;
use App\Group;
use App\Http\Resources\GroupResource;
use App\Permission;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class GroupController extends Controller
{
    private $group;

    function __construct(Group $group)
    {
        $this->group = $group;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return GroupResource::collection(Group::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        if (!Auth::user()->hasPermissionTo($request->group_slug, 'create-group')) {
            return response()->json([
                'message' => 'Action forbidden. You don\'t have the permission to do that',
                'redirect' => 'home'
            ], Response::HTTP_FORBIDDEN);
        }
        // Validate the data;
        $request->validate([
            'name' => ['required', 'string', 'max:30', 'unique:tenant.groups,name'],
            'users' => ['required', 'array']
        ]);

        // Create the group;
        $group = $this->group->create([
            'name' => ucwords($request->name),
            'description' => $request->description,
            'created_by' => $request->user()->id,
        ]);

        event(new GroupCreatedEvent($group));

        $adminRole = Role::admin($group->slug)->first();
        $memberRole = Role::member($group->slug)->first();
        foreach ($request->users as $user_id) {
            $group->users()->attach($user_id, ['added_by' => $group->created_by]);
            User::find($user_id)->roles()->attach([$adminRole->id, $memberRole->id]);
        }

        return response()->json([
            'message' => 'Group created',
            'payload' => GroupResource::make($group)
        ], Response::HTTP_OK);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Group $group)
    {
        if (!Auth::user()->hasPermissionTo($request->group_slug, 'edit-group')) {
            return response()->json([
                'message' => 'Action forbidden. You don\'t have the permission to do that',
                'redirect' => 'home'
            ], Response::HTTP_FORBIDDEN);
        }

        // Modify the slug for all other roles;
        // Modify the slug for all other roles;
        $request->validate([
            'name' => ['required', 'string', 'max:30', 'unique:tenant.groups,name,' . $group->id],
            'users' => ['required', 'array']
        ]);

        $oldSlug = $group->slug;
        $group->slug = null;

        // Update the group using the new info;
        $group->update([
            'name' => Str::title($request->name),
            'description' => $request->description,
            'updated_at' => now()
        ]);

        // Modify the slug for the roles;
        Permission::where('group_slug', $oldSlug)->update(['group_slug' => $group->slug]);
        Role::where('group_slug', $oldSlug)->update(['group_slug' => $group->slug]);

        $memberRole = Role::member($group->slug)->first();
        $adminRole = Role::admin($group->slug)->first();

        // We take all managers that have been removed and remove their roles and permissions for that group;
        // We do this in order to disable the user option to see the group menu;
        $removedUsers = array_diff($group->users()->pluck('user_id')->toArray(),$request->users);

        $groupPermissions = Permission::where('group_slug',$group->slug)->pluck('id')->toArray();
        $groupRoles = Role::where('group_slug',$group->slug)->pluck('id')->toArray();

        foreach ($removedUsers as $user_id) {
            User::find($user_id)->roles()->detach($groupRoles);
            User::find($user_id)->permissions()->detach($groupPermissions);
        }

        $users = [];
        foreach ($request->users as $user_id) {
            $users[$user_id] = ['added_by' => Auth::user()->id];
            User::find($user_id)->roles()->syncWithoutDetaching([$memberRole->id, $adminRole->id]);
        }
        $group->users()->sync($users);

        return response()->json([
            'message' => 'Group updated',
            'payload' => GroupResource::make($group)
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        if (!Auth::user()->hasPermissionTo($request->group_slug, 'delete-group')) {
            return response()->json([
                'message' => 'Action forbidden. You don\'t have the permission to do that',
                'redirect' => 'home'
            ], Response::HTTP_FORBIDDEN);
        }

        event(new GroupDestroyedEvent(Group::find($id)));
        $this->group->destroy($id);

        return response()->json([
            'message' => 'Group successfully removed'
        ], Response::HTTP_OK);
    }
}