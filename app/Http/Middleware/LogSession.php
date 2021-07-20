<?php

namespace Manivelle\Http\Middleware;

use Closure;
use Log;
use Session;

class LogSession
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
        Log::info(Session::getId());

        return $next($request);
    }
}
