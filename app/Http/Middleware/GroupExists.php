<?php

namespace App\Http\Middleware;

use App\Group;
use Closure;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class GroupExists
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->has('group_slug'))
        if (!$this->groupExists($request->group_slug)) {
            return response()->json([
                'message' => 'The request was made for a group that dosen\'t exist. You have been redirected to home page.',
                'redirect' => 'home'
            ], Response::HTTP_NOT_EXTENDED);
        }

        return $next($request);
    }

    private function groupExists($group_slug)
    {
        if (Group::where('slug', $group_slug)->count()) {
            return true;
        }
        return false;
    }
}
