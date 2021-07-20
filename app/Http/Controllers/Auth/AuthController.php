<?php

namespace Manivelle\Http\Controllers\Auth;

use Manivelle\User;
use Localizer;
use Auth;

use Panneau\Http\Controllers\AuthController as BaseAuthController;

class AuthController extends BaseAuthController
{

    // Just to prevent access to user creation page
    protected $registerView = null;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => ['getLogout', 'logout']]);
    }
    
    /**
     * Get the path to the redirection after login.
     *
     * @return string
     */
    public function redirectPath()
    {
        $user = Auth::user();
        $locale = $user->locale;
        $organisation = app('request')->route('organisation');
        
        if ($organisation) {
            return route(Localizer::routeName('organisation.home', $locale), [$organisation->slug]);
        } else {
            return Localizer::route('home', $locale);
        }
    }
}
