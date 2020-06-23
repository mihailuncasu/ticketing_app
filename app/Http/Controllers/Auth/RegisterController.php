<?php

namespace App\Http\Controllers\Auth;

use App\Tenant;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $invalidSubdomains = config('app.invalid_subdomains');

        return Validator::make($data, [
            'domain' => [
                'required',
                'string',
                Rule::notIn($invalidSubdomains),
                'regex:/^[A-Za-z0-9](?:[A-Za-z0-9\-]{0,61}[A-Za-z0-9])$/'
            ],
            'fqdn' => ['required', 'string', 'unique:system.hostnames'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $data = $request->all();
        if (isset($data['domain'])) {
            $fqdn = $data['domain'] . '.' . env('TENANT_URL_BASE');
            $request->merge(['fqdn' => $fqdn]);
        }

        // Request data;
        $validator = $this->validator($request->all());
        $validator->validate();

        Tenant::create($request->fqdn);

        // Use only the validated data;
        $user = $this->create($validator->validated());

        $user->sendEmailVerificationNotification();

        return response()->json([
            'redirect' => 'loginDomain',
            'message' => 'Registration successful. A verification link has been sent to your e-mail address.'
        ], Response::HTTP_CREATED);
    }

    /**
     * Checks if the fqdn is valid.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkDomain(Request $request)
    {
        $data = $request->all();
        if (isset($data['domain'])) {
            $fqdn = $data['domain'] . '.' . env('TENANT_URL_BASE');
            $data['fqdn'] = $fqdn;
        }

        $invalidSubdomains = config('app.invalid_subdomains');

        $validator = Validator::make($data, [
            'domain' => [
                'required',
                'string',
                Rule::notIn($invalidSubdomains),
                'regex:/^[A-Za-z0-9](?:[A-Za-z0-9\-]{0,61}[A-Za-z0-9])$/'
            ],
            'fqdn' => ['required', 'string', 'unique:system.hostnames'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'valid' => false,
                'error' => 'The given domain is invalid or already in use'
            ], 422);
        } else {
            return response()->json([
                'valid' => true,
                'message' => 'Domain is available'
            ], 200);
        }
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => ucfirst($data['name']),
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // Add avatar img;
        $avatar_url = 'https://img.favpng.com/2/12/12/computer-icons-portable-network-graphics-user-profile-avatar-png-favpng-L1ihcbxsHbnBKBvjjfBMFGbb7.jpg';
        $user->addMediaFromUrl($avatar_url)->toMediaCollection('avatar');

        $user->assignRole('admin');

        return $user;
    }
}
