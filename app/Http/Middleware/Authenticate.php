<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Response;
use Illuminate\Contracts\Auth\Factory as Auth;

class Authenticate
{
    /**
     * The authentication factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param \Illuminate\Contracts\Auth\Factory $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param \Illuminate\Http\Request $request
     * @return string
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (!$request->user($guard)) {
            return response()->json([
                'message' => 'The access token is either missing or incorrect. You will be logged out.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $this->auth->shouldUse($guard);
        return $next($request);
    }
}
