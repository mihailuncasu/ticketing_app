<?php

namespace App\Http\Controllers\Api;

use App\Events\UserCreatedEvent;
use App\Http\Resources\UserResource;
use App\Notifications\RegisterUser;
use App\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        return UserResource::collection(User::all());
    }

    /**
     * Display user data as resource.
     *
     * @return Application|ResponseFactory|\Illuminate\Http\Response
     */
    public function profile()
    {
        $data = new UserResource(Auth::user());
        return response([
            'profile' => $data,
            'menu' => Auth::user()->getUserMenu()
        ], Response::HTTP_CREATED);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        if (!Auth::user()->hasPermissionTo($request->group_slug, 'create-user')) {
            return response()->json([
                'message' => 'Action forbidden. You don\'t have the permission to do that',
                'redirect' => 'home'
            ], Response::HTTP_FORBIDDEN);
        }

        if ($request->auto) {
            $password = Str::random(10);
            $request->merge([
                'password' => $password,
                'password_confirmation' => $password
            ]);
        }

        $request->validate([
            'name' => 'required',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:tenant.users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ]);

        $user = User::create([
            'name' => Str::title($request->name),
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        // Assign the avatar;
        event(new UserCreatedEvent($user));

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

        // Also, after we create the user we send a mail to the specific mail address with the password;
        $user->notify(new RegisterUser($user, $request->password));
        $user->markEmailAsVerified();

        return response()->json([
            'message' => 'User created',
            'payload' => UserResource::make($user)
        ], Response::HTTP_CREATED);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, User $user)
    {
        if (!Auth::user()->hasPermissionTo($request->group_slug, 'edit-user')) {
            return response()->json([
                'message' => 'Action forbidden. You don\'t have the permission to do that',
                'redirect' => 'home'
            ], Response::HTTP_FORBIDDEN);
        }

        $request->validate([
            'name' => 'required',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:tenant.users,email,' . $user->id],
        ]);

        $user->update([
            'name' => Str::title($request->name),
            'email' => $request->email,
            'updated_at' => now()
        ]);

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
            'message' => 'User updated',
            'payload' => UserResource::make($user)
        ], Response::HTTP_ACCEPTED);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        if (!Auth::user()->hasPermissionTo($request->group_slug, 'delete-user')) {
            return response()->json([
                'message' => 'Action forbidden. You don\'t have the permission to do that',
                'redirect' => 'home'
            ], Response::HTTP_FORBIDDEN);
        }
        if (Auth::user()->id != $id) {
            User::destroy($id);
            return response()->json([
                'message' => 'User successfully removed'
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'Action denied. Cannot delete yourself.'
            ], Response::HTTP_FORBIDDEN);
        }
    }
}