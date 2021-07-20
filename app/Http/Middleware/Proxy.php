<?php

namespace Manivelle\Http\Middleware;

use Closure;
use View;
use Manivelle\Models\Organisation  as OrganisationModel;

class Proxy
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
        $request->setTrustedProxies([
            $request->getClientIp()
        ]);

        return $next($request);
    }
}
