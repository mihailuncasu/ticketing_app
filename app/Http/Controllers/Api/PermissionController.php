<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PermissionResource;
use Illuminate\Http\Request;
use App\Permission;
use Symfony\Component\HttpFoundation\Response;

class PermissionController extends Controller
{
    /**
     * @var Permission
     */
    private $permission;

    function __construct(Permission $permission)
    {
        $this->permission = $permission;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
         return PermissionResource::collection($this->permission->all());
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
            'name' => ['required', 'string', 'max:255', 'unique:tenant.permissions,name']
        ]);
        $permission = $this->permission->create([
            'name' => $request->name,
            'display_name' => ucwords($request->name)
        ]);

        return response([
            'message' => 'Permission created',
            'payload' => PermissionResource::make($permission)
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:tenant.permissions,name,'.$permission->id]
        ]);

        $permission->update([
            'name' => $request->name,
            'display_name' => ucwords($request->name),
            'updated_at' => now()
        ]);

        return response([
            'message' => 'Permission updated',
            'payload' => PermissionResource::make($permission)
        ],Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->permission->destroy($id);

        return response()->json([
            'message' => 'Permission successfully removed'
        ], Response::HTTP_OK);
    }
}
