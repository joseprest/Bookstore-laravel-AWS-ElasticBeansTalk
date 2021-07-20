<?php namespace Manivelle\View\Composers;

use Auth;
use Manivelle;
use Image;
use Cache;
use App;
use Manivelle\User;
use Route;
use Localizer;

class HeaderLocaleComposer
{
    public function compose($view)
    {
        $route = Route::current();
        $localizable = Localizer::isRouteLocalizable($route);
        $localeNames = config('locale.localeNames');
        $currentLocale = App::getLocale();
        $localizedRoutes = [];
        $locales = [];

        // If localizable, get URL of this route in all locales
        if ($localizable) {
            $baseRouteName = Localizer::routeNameInverse($route->getName());
            $allLocales = Localizer::getAllLocales();
            $routeParams = $route->hasParameters() ? $route->bindParameters(app('request')):[];
            $localizedRoutes = Localizer::getLocalizedRoutes($baseRouteName, $allLocales, $routeParams);
        }

        // We keep only non-null URLs (null means no URL in
        // this locale)
        $localizedRoutes = array_filter($localizedRoutes);

        // We create the $locales array with data for each
        // locale URL
        foreach ($localizedRoutes as $localeCode => $routeData) {
            if (is_null($routeData)) {
                continue;
            }

            $localeName = array_get($localeNames, $localeCode, $localeCode);
            $isCurrent = $localeCode == $currentLocale;

            $locales[$localeCode] = [
                'url' => $routeData,
                'current' => $isCurrent,
                'name' => $localeName
            ];
        }

        // We set the view variables
        $view->hasLocales = $localizable && count($locales) > 0;
        $view->currentLocale = $currentLocale;
        $view->currentLocaleName = array_get($localeNames, $currentLocale, $currentLocale);
        $view->locales = $locales;
    }
}
