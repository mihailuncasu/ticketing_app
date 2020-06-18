<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\UserResource;
use App\Notifications\RegisterUser;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return UserResource::collection(User::all());
    }

    /**
     * Display user data as resource.
     *
     * @return UserResource
     */
    public function profile()
    {
        return new UserResource(Auth::user());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Check if the request contains the password;
        // If not, then we generate a random password and add it to request;
        if ($request->auto) {
            $password = Str::random(10);
            $request->merge([
                'password' => $password,
                'password_confirmation' => $password
            ]);
        }

        $request->validate([
           'name'=>'required',
           'email'=> ['required', 'string', 'email', 'max:255', 'unique:tenant.users,email'],
           'password'=> ['required', 'string', 'min:8', 'confirmed']
        ]);

        $user= User::create([
            'name'=> $request->name,
            'email'=> $request->email,
            'password'=> Hash::make($request->password)
        ]);

        if ($request->has('role')) {
            $user->assignRole($request->role['name']);
        }

        if ($request->has('permissions')) {
            $user->givePermissionTo(collect($request->permissions)->pluck('id')->toArray());
        }

        // Also, after we create the user we send a mail to the specific mail address with the password;
        $user->notify(new RegisterUser($user, $request->password));
        $user->markEmailAsVerified();

        return response([
            'message'=>'User Created',
            'payload'=>UserResource::make($user)
        ], Response::HTTP_CREATED);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'=>'required',
            'email'=>'required|email',
        ]);

        $user->update([
            'name'=> $request->name,
            'email'=> $request->email,
            'updated_at' => now()
        ]);

        if ($request->has('role')) {
            $user->syncRoles($request->role['name']);
        }

        if ($request->has('permissions')) {
            $user->syncPermissions(collect($request->permissions)->pluck('id')->toArray());
        }

        return response([
            'message'=>'User Updated',
            'payload'=> UserResource::make($user)
        ], Response::HTTP_ACCEPTED);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        User::destroy($id);
        return response()->json([
            'message' => 'User successfully removed'
        ], 200);
    }
}