<?php

namespace App\Http\Controllers\Api;

use App\Group;
use App\Http\Resources\UserResource;
use App\Permission;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $groupMembers = Group::where('slug', $request->group_slug)->first()->users;
        return UserResource::collection($groupMembers);
    }

    public function possibleMembers(Request $request) {
        $groupMembers = Group::where('slug', $request->group_slug)->first()->users;
        $possibleMembers = User::whereNotIn('id', $groupMembers->pluck('id'))->get();
        return UserResource::collection($possibleMembers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        if (!Auth::user()->hasPermissionTo($request->group_slug, 'add-member')) {
            return response()->json([
                'message' => 'Action forbidden. You don\'t have the permission to do that',
                'redirect' => 'home'
            ], Response::HTTP_FORBIDDEN);
        }

        $user = User::find($request->user['id']);
        $group = Group::where('slug', $request->group_slug)->first();
        if ($group->users()->where('user_id', $user->id)->count()) {
            return response()->json([
                'message' => 'The selected user is already a member of this group',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $group->users()->attach([$user->id => ['added_by' => Auth::user()->id]]);

        if ($request->has('roles')) {
            $user->assignRolesUsingSlug(
                $request->group_slug,
                collect($request->roles)
                    ->pluck('slug')
                    ->toArray()
            );
        }

        if ($request->has('permissions')) {
            $user->givePermissionsToUsingSlug(
                $request->group_slug,
                collect($request->permissions)
                    ->pluck('slug')
                    ->toArray()
            );
        }

        return response()->json([
            'message' => 'Member added',
            'payload' => UserResource::make($user)
        ], Response::HTTP_CREATED);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        if (!Auth::user()->hasPermissionTo($request->group_slug, 'edit-member')) {
            return response()->json([
                'message' => 'Action forbidden. You don\'t have the permission to do that',
                'redirect' => 'home'
            ], Response::HTTP_FORBIDDEN);
        }

        $user = User::find($request->user['id']);
        $group = Group::where('slug', $request->group_slug)->first();
        if (!$group->users()->where('user_id', $user->id)->count()) {
            return response()->json([
                'message' => 'The selected user is not a member of this group',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($request->has('roles')) {
            $user->assignRolesUsingSlug(
                $request->group_slug,
                collect($request->roles)
                    ->pluck('slug')
                    ->toArray()
            );
        }

        if ($request->has('permissions')) {
            $user->givePermissionsToUsingSlug(
                $request->group_slug,
                collect($request->permissions)
                    ->pluck('slug')
                    ->toArray()
            );
        }

        return response()->json([
            'message' => 'Member edited',
            'payload' => UserResource::make($user)
        ], Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        if (!Auth::user()->hasPermissionTo($request->group_slug, 'remove-member')) {
            return response()->json([
                'message' => 'Action forbidden. You don\'t have the permission to do that',
                'redirect' => 'home'
            ], Response::HTTP_FORBIDDEN);
        }

        // Detach user from group;
        $group = Group::where('slug', $request->group_slug)->first();
        $group->users()->detach($id);

        // Remove group permissions and roles;
        $user = User::find($id);
        $groupPermissions = Permission::where('group_slug',$group->slug)->pluck('id')->toArray();
        $groupRoles = Role::where('group_slug',$group->slug)->pluck('id')->toArray();
        $user->roles()->detach($groupRoles);
        $user->permissions()->detach($groupPermissions);

        return response()->json([
            'message' => 'The user is no longer a member'
        ], Response::HTTP_OK);
    }
}
