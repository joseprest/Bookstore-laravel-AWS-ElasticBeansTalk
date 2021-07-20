<?php

namespace Manivelle\Http\Middleware;

use Closure;
use Gate;

class AuthenticateOrganisation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $organisation = $request->route('organisation');
        
        if (Gate::denies('view', $organisation)) {
            return abort(403);
        }

        return $next($request);
    }
}
