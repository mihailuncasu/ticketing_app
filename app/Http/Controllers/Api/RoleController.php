<?php

namespace App\Http\Controllers\Api;

use App\Role;
use Illuminate\Http\Request;
use App\Http\Resources\RoleResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;


class RoleController extends Controller
{

    private $role;

    function __construct(Role $role)
    {
        $this->role = $role;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return RoleResource::collection(Role::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:tenant.roles,name']
        ]);
        $role = $this->role->create([
            'name' => $request->name,
            'display_name' => ucwords($request->name)
        ]);

        if ($request->has('permissions')) {
            $role->givePermissionTo(collect($request->permissions)->pluck('id')->toArray());
        }

        return response([
            'message' => 'Role created',
            'payload' => RoleResource::make($role)
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Role $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role) {

        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:tenant.roles,name,'.$role->id]
        ]);

        $role->update([
            'name' => $request->name,
            'display_name' => ucwords($request->name),
            'updated_at' => now()
        ]);

        if ($request->has('permissions')) {
            $role->givePermissionsToUsingSlug(
                $request->group_slug,
                collect($request->permissions)
                    ->pluck('slug')
                    ->toArray()
            );
        }

        return response([
            'message' => 'Role updated',
            'payload' => RoleResource::make($role)
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id) {
        $this->role->destroy($id);

        return response()->json([
            'message' => 'Role successfully removed'
        ], Response::HTTP_OK);
    }
}
