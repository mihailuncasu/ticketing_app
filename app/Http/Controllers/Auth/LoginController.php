<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Hyn\Tenancy\Models\Hostname;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);

        $credentials = request(['email', 'password']);

        // Attempt to login the user;
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Incorrect e-mail or password',
            ], Response::HTTP_NOT_ACCEPTABLE);
        } else {
            // Get user data;
            // Check if the email has been verified;
            $user = $request->user();
            if ($user->email_verified_at !== null) {
                $tokenResult = $user->createToken('Personal Access Token');
                $token = $tokenResult->token;

                // Add 1 week if remember me is checked;
                if ($request->remember_me) {
                    $token->expires_at = Carbon::now()->addWeeks(1);
                }

                // Save the new created token;
                $token->save();

                return response()->json([
                    'access_token' => $tokenResult->accessToken,
                    'token_type' => 'Bearer',
                    'expires_at' => Carbon::parse(
                        $tokenResult->token->expires_at
                    )->toDateTimeString(),
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'message' => "Welcome, " . Auth::user()->name
                ], Response::HTTP_ACCEPTED);
            } else {
                return response()->json([
                    'message' => 'Please verify your e-mail',
                ], Response::HTTP_UNAUTHORIZED);
            }
        }
    }

    public function domainLogin(Request $request)
    {
        $invalidSubdomains = config('app.invalid_subdomains');
        $validatedData = $request->validate([
            'domain' => [
                'required',
                'string',
                Rule::notIn($invalidSubdomains),
                'regex:/^[A-Za-z0-9](?:[A-Za-z0-9\-]{0,61}[A-Za-z0-9])$/'
            ],
        ]);
        $fqdn = $validatedData['domain'] . '.' . env('TENANT_URL_BASE');
        $hostExists = Hostname::where('fqdn', $fqdn)->exists();
        $port = $request->server('SERVER_PORT') == 8000 ? ':8000' : '';
        if ($hostExists) {
            return response()->json([
                'redirect' => ($request->secure() ? 'https://' : 'http://') . $fqdn . $port . '/login',
                'message' => 'You are being redirected to the given domain login page'
            ], 201);
        } else {
            return response()->json([
                'redirect' => url('register'),
                'message' => 'Domain not registered yet'
            ], 403);
        }
    }

    public function logout()
    {
        auth()->user()->tokens->each(function ($token, $key) {
            $token->delete();
        });

        return response()->json([
            'message' => 'Logged out successfully'
        ], 200);
    }
}
