<?php

namespace Manivelle\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Manivelle;

use Manivelle\Models\Organisation;
use Manivelle\Models\OrganisationInvitation;
use Manivelle\Models\Channel;
use Manivelle\Models\Screen;
use Manivelle\Models\Bubble;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'Manivelle\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot(Router $router)
    {
        parent::boot($router);

        $this->bindOrganisation($router);
        $this->bindScreens($router);
        $this->bindScreenUUID($router);
        $this->bindInvitation($router);
        $this->bindBubbleId($router);
    }
    
    public function register()
    {
        parent::register();
        
        $this->app->bind('Illuminate\Routing\ResourceRegistrar', 'Manivelle\Http\Routing\ResourceRegistrar');
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function map(Router $router)
    {
        $router->group(['namespace' => $this->namespace], function ($router) {
            require app_path('Http/routes.php');
        });
        if (config('app.worker')) {
            $router->group(['namespace' => $this->namespace], function ($router) {
                require app_path('Http/routesWorker.php');
            });
        }
    }

    /**
     * Bind the 'organisation' URL param which maps to an
     * Organisation
     *
     * @param  Router $router
     */
    protected function bindOrganisation(Router $router)
    {
        $router->bind('organisation', function ($slug, $route) {
            $organisation = Organisation::where('slug', $slug)
                ->first();
            return $organisation;
        });
    }

    /**
     * Bind the 'screens' URL param which maps to a
     * Screen. If the request comes with an organisation,
     * we search the screen within this organisation's screen.
     *
     * If the request is for an organisation and the screen is
     * not in this organisation or if the screen doesn't exist,
     * ModelNotFoundException is thrown.
     *
     * @param  Router $router
     * @throws ModelNotFoundException
     */
    protected function bindScreens(Router $router)
    {
        $router->bind('screens', function ($id, $route) {
            $organisation = $route->parameter('organisation');
            
            if ($organisation) {
                $organisationScreens = $organisation->screens()
                                        ->with([
                                            'screen',
                                            'screen.channels'
                                        ])
                                        ->get();
                $organisationScreen = $organisationScreens->first(function ($index, $screen) use ($id) {
                    return (string)$id === (string)$screen->screen_id;
                });
                
                if ($organisationScreen) {
                    return $organisationScreen;
                } else {
                    throw (new ModelNotFoundException)->setModel(Screen::class);
                }
            }

            return Screen::findOrFail($id);
        });
    }

    /**
     * Bind the 'screen_uuid' URL param which maps to a
     * Screen. If the request comes with an organisation,
     * we search the screen within this organisation's screen.
     *
     * If the request is for an organisation and the screen is
     * not in this organisation or if the screen doesn't exist,
     * ModelNotFoundException is thrown.
     *
     * @param  Router $router
     */
    protected function bindScreenUUID(Router $router)
    {
        $router->bind('screen_uuid', function ($uuid, $route) {
            $organisation = $route->parameter('organisation');
            
            if ($organisation) {
                $screens = $organisation->screens()
                                        ->with([
                                            'screen',
                                            'screen.channels'
                                        ])
                                        ->get();
                $screen = $screens->first(function ($index, $screen) use ($uuid) {
                    return
                        (string)$uuid === (string)$screen->uuid
                        || (string)$uuid === (string)$screen->slug;
                });
                
                if ($screen) {
                    return $screen;
                } else {
                    throw (new ModelNotFoundException)->setModel(Screen::class);
                }
            }
            
            $screen = Screen::where('uuid', $uuid)->first();
            
            return $screen ? $screen:Screen::where('slug', $uuid)->first();
        });
    }

    /**
     * Bind the 'invitation' URL param which maps to an
     * Invitation.
     *
     * @param  Router $router
     */
    protected function bindInvitation(Router $router)
    {
        $router->bind('invitation', function ($key, $route) {
            return OrganisationInvitation::where('invitation_key', $key)->first();
        });
    }

    /**
     * Bind the 'bubble_id' URL param which maps to atech@manivelle.imap_open(mailbox, username, password)
     * Bubble.
     *
     * @param  Router $router
     */
    protected function bindBubbleId(Router $router)
    {
        $router->bind('bubble_id', function ($id, $route) {
            return Bubble::findOrFail($id);
        });
    }
}
