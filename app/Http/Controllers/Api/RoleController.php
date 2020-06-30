<?php

namespace App\Http\Controllers\Api;

use App\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Resources\RoleResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;


class RoleController extends Controller
{

    private $role;

    function __construct(Role $role)
    {
        $this->role = $role;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @param Role|null $role
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data, Role $role = null)
    {
        $id = $role ? $role->id : null;
        return Validator::make($data, [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles')->ignore($id)->where('group_slug', $data['group_slug'])
            ],
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse|AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        return RoleResource::collection($this->role->where('group_slug', $request->group_slug)->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        if (!Auth::user()->hasPermissionTo($request->group_slug, 'create-role')) {
            return response()->json([
                'message' => 'Action forbidden. You don\'t have the permission to do that',
                'redirect' => 'home'
            ], Response::HTTP_FORBIDDEN);
        }

        $request->name = Str::lower($request->name);

        $data = $this->validator($request->all())->validate();

        $role = $this->role->create([
            'name' => $data['name'],
            'display_name' => Str::title($data['name']),
            'group_slug' => $request->group_slug
        ]);

        if ($request->has('permissions')) {
            $role->givePermissionsToUsingSlug(
                $request->group_slug,
                collect($request->permissions)
                    ->pluck('slug')
                    ->toArray()
            );
        }

        return response()->json([
            'message' => 'Role created',
            'payload' => RoleResource::make($role)
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Role $role
     * @return JsonResponse
     */
    public function update(Request $request, Role $role)
    {
        if (!Auth::user()->hasPermissionTo($request->group_slug, 'edit-role')) {
            return response()->json([
                'message' => 'Action forbidden. You don\'t have the permission to do that',
                'redirect' => 'home'
            ], Response::HTTP_FORBIDDEN);
        }

        $request->name = Str::lower($request->name);
        $role->slug = null;

        $data = $this->validator($request->all(), $role)->validate();

        $role->update([
            'name' => $data['name'],
            'display_name' => Str::title($data['name']),
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

        return response()->json([
            'message' => 'Role updated',
            'payload' => RoleResource::make($role)
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
        if (!Auth::user()->hasPermissionTo($request->group_slug, 'delete-role')) {
            return response()->json([
                'message' => 'Action forbidden. You don\'t have the permission to do that',
                'redirect' => 'home'
            ], Response::HTTP_FORBIDDEN);
        }

        $this->role->destroy($id);

        return response()->json([
            'message' => 'Role successfully removed'
        ], Response::HTTP_OK);
    }
}
