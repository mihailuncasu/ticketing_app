<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\Factory as Auth;

class EnsureEmailIsVerified
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
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string|null $redirectToRoute
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (!$request->user($guard)) {
            return response()->json([
                'message' => 'The access token is either missing or incorrect.',
            ], 401);
        } else if
        ($request->user($guard) instanceof MustVerifyEmail &&
            !$request->user($guard)->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Your email address is not verified.',
            ], 403);
        }
        $this->auth->shouldUse($guard);
        return $next($request);
    }
}