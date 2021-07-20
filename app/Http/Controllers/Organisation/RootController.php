<?php namespace Manivelle\Http\Controllers\Organisation;

use Manivelle\Http\Controllers\Controller;
use Localizer;
use Auth;

/**
 * Controller that manages the site's root (/). Since we use
 * different languages, we redirect the user (logged in or not)
 * to the home page in its language.
 */
class RootController extends Controller
{
    public function index($organisation)
    {
        $user = \Auth::user();

        return redirect()->route(
            Localizer::routeName('organisation.home', $user->locale),
            [$organisation->slug]
        );
    }
}
