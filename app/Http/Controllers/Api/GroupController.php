<?php

namespace App\Http\Controllers\Api;

use App\Group;
use App\Http\Resources\GroupResource;
use App\Permission;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the data;
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:tenant.groups,name'],
            'users' => ['required', 'array']
        ]);

        // Create the group;
        $group = $this->group->create([
            'name' => ucwords($request->name),
            'description' => $request->description,
            'created_by' => $request->user()->id,
        ]);

        // Attach the users to the group, also specify who added them;
        foreach ($request->users as $user_id) {
            $group->users()->attach($user_id, ['added_by' => $group->created_by]);
            // Also, when we attach the users to a freshly created group we give them the "Group manager" role for that group;
            /*$permissions = ['add user', 'add role', 'edit user', 'edit role'];

            $groupPermissions = [];
            foreach ($permissions as $permission) {
                $groupPermissions[] = Permission::create([
                    'name' => $permission,
                    'display_name' => ucwords($permission),
                    'group_slug' => $group->slug,
                    'guard_name' => $group->slug
                ]);
            }

            $managerRole = Role::create([
                'name' => 'group member',
                'display_name' => 'Group Member',
                'group_slug' => $group->slug,
                'guard_name' => $group->slug
            ]);

            $memberRole = Role::create([
                'name' => 'group manager',
                'display_name' => 'Group Manager',
                'group_slug' => $group->slug,
                'guard_name' => $group->slug
            ]);

            $managerRole->givePermissionTo($groupPermissions);

            $user = User::find($user_id);

            $user->assignRole([$managerRole, $memberRole]);*/
        }

        return response([
            'message' => 'Role Created',
            'payload' => GroupResource::make($group)
        ], Response::HTTP_OK);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->group->destroy($id);

        return response()->json([
            'message' => 'Group successfully removed'
        ], Response::HTTP_OK);
    }
}