<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PermissionResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Permission;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
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
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @param Permission $permission
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data, Permission $permission = null)
    {
        $id = $permission ? $permission->id : null;
        return Validator::make($data, [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('permissions')->ignore($id)->where('group_slug', $data['group_slug'])
            ],
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        return PermissionResource::collection($this->permission->where('group_slug', $request->group_slug)->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        if (Gate::denies("create-permission", $request->group_slug)) {
            return response()->json([
                'message' => 'Action forbidden. You don\'t have the permission to do that',
                'redirect' => 'home'
            ], Response::HTTP_FORBIDDEN);
        }

        $request->name = Str::lower($request->name);

        $data = $this->validator($request->all())->validate();

        $permission = $this->permission->create([
            'name' => $data['name'],
            'display_name' => Str::title($request['name']),
            'group_slug' => $request->group_slug
        ]);

        return response()->json([
            'message' => 'Permission created',
            'payload' => PermissionResource::make($permission)
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, Permission $permission)
    {
        if (Gate::denies("edit-permission", $request->group_slug)) {
            return response()->json([
                'message' => 'Action forbidden. You don\'t have the permission to do that',
                'redirect' => 'home'
            ], Response::HTTP_FORBIDDEN);
        }

        $request->name = Str::lower($request->name);
        $permission->slug = null;

        $data = $this->validator($request->all(), $permission)->validate();

        $permission->update([
            'name' => $data['name'],
            'display_name' => Str::title($data['name']),
            'updated_at' => now()
        ]);

        return response()->json([
            'message' => 'Permission updated',
            'payload' => PermissionResource::make($permission)
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        if (Gate::denies("delete-permission", $request->group_slug)) {
            return response()->json([
                'message' => 'Action forbidden. You don\'t have the permission to do that',
                'redirect' => 'home'
            ], Response::HTTP_FORBIDDEN);
        }

        $this->permission->destroy($id);

        return response()->json([
            'message' => 'Permission successfully removed'
        ], Response::HTTP_OK);
    }
}
