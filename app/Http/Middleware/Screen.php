<?php

namespace Manivelle\Http\Middleware;

use Closure;
use Illuminate\Contracts\View\Factory as ViewFactory;

class Screen
{
    /**
     * The view factory implementation.
     *
     * @var \Illuminate\Contracts\View\Factory
     */
    protected $view;

    /**
     * Create a new error binder instance.
     *
     * @param  \Illuminate\Contracts\View\Factory  $view
     * @return void
     */
    public function __construct(ViewFactory $view)
    {
        $this->view = $view;
    }
    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $screen = $request->route('screens');
        
        if ($screen) {
            $this->view->share('currentScreen', $screen);
        }

        return $next($request);
    }
}
