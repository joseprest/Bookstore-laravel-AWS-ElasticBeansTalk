<?php namespace Manivelle\Http\Controllers\Screen;

use Panneau;
use Cache;
use App;

use Manivelle\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Manivelle\Models\Screen;

class ScreenController extends Controller
{
    public function index(Request $request, Screen $screen)
    {
        \Debugbar::disable();

        // Get the locale of the screen and set it as global locale
        $screenLocale = $screen->getDefaultLocale();
        App::setLocale($screenLocale);

        return view('screen.index', [
            'screen' => $screen,
            'organisation' => !$screen->organisations->isEmpty() ? $screen->organisations[0] : null
        ]);
    }
}
