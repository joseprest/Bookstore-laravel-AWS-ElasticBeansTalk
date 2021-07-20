<?php namespace Manivelle\Http\Controllers;

use Localizer;

/**
 * Controller that manages the site's root (/). Since we use
 * different languages, we redirect the user (logged in or not)
 * to the home page in its language.
 */
class RootController extends Controller
{

    public function index()
    {
        return redirect()->route(Localizer::routeName('home'));
    }
}
