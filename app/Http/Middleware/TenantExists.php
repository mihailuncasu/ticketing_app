<?php

namespace App\Http\Middleware;

use Hyn\Tenancy\Models\Hostname;
use Closure;

class TenantExists
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
        $fqdn = $request->getHost();
        if (!$this->tenantExists($fqdn)) {
            if ($request->isMethod('get')) {
                abort(403, 'The current domain is not registered. Yet.');
            } else {
                return response()->json([
                    'message' => 'The current domain is not registered. Yet.'
                ], 403);
            }
        }
        if ($request->user() == null) {
            if (!$this->tenantExists($fqdn)) {
                if ($request->isMethod('get')) {
                    abort(403, 'The current domain is not registered. Yet.');
                } else {
                    return response()->json([
                        'message' => 'The current domain is not registered. Yet.'
                    ], 403);
                }
            }
        }

        return $next($request);
    }

    private function tenantExists($fqdn)
    {
        return Hostname::where('fqdn', $fqdn)->exists();
    }
}
