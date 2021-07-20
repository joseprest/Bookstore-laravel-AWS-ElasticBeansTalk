<?php

namespace Manivelle\Http\Middleware;

use Closure;
use Localizer;
use Auth;
use Panneau\Http\Middleware\Authenticate as AuthenticateBase;

class Authenticate extends AuthenticateBase
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest(Localizer::route('home'));
            }
        }

        return $next($request);
    }
}
