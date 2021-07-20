<?php

namespace Manivelle\Services;

/**
 * Methods for Manivelle specific localization.
 */

class Localizer
{
    protected $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * Calls Laravel's route() function with the specified routeName
     * localized in the specified locale.
     *
     * This method can be called with the same parameters as Laravel's
     * `route()` but you can optionnaly specify a locale as the second
     * parameter and the other arguments after.
     *
     * @param string $routeName The non-localized route name (ex: 'home')
     * @param string|null $locale Optionnale locale to use. If this argument
     *                            is neither a string or null, it is passed to
     *                            route() as parameter argument.
     * @param All other params are passed to route()
     * @return string Returned value of Laravel's route()
     */
    public function route($routeName, $locale = null)
    {
        $withLocale = is_null($locale) || is_string($locale);
        $func_args = func_get_args();
        $locale = $withLocale ? $locale : null;
        $localizedRouteName = $this->routeName($routeName, $locale);

        // If a locale was passed, the arguments to pass to routes
        // are the ones after $locale, else the arguments are the ones
        // after $routeName
        if ($withLocale) {
            $routeArgs = array_slice($func_args, 2);
        } else {
            $routeArgs = array_slice($func_args, 1);
        }

        // Add the route name to the args
        array_unshift($routeArgs, $localizedRouteName);

        return call_user_func_array('route', $routeArgs);
    }

    /**
     * Localizes a route name to the specified locale.
     *
     * @param  string $routeName
     * @param  string $locale    Locale to use. If not set, see getEffectiveLocale()
     * @return string
     */
    public function routeName($routeName, $locale = null)
    {
        $locale = $this->getEffectiveLocale($locale);
        return $routeName . '.' . $locale;
    }

    /**
     * From a localized route name, return the base name without
     * the localization. Inverse function of routeName.
     * `routeNameInverse( routeName( x ) ) = x`
     *
     * @param  string $routeName
     * @param  string $locale The locale to remove
     * @return string
     */
    public function routeNameInverse($routeName, $locale = null)
    {
        $locale = $this->getEffectiveLocale($locale);
        $localePart = '.' . $locale;

        if (ends_with($routeName, $localePart)) {
            return substr($routeName, 0, -strlen($localePart));
        }

        return $routeName;
    }

    /**
     * Returns true if the supplied route could be localizable.
     * Only named routes can be localizabled. Some routes are
     * null (ex: 404 page), so false is returned for them.
     * Doesn't check if this route is really defined in other
     * locales, just if it meets requirements to be localized.
     *
     * @param  Illuminate\Routing\Route|null  $route
     * @return boolean
     */
    public function isRouteLocalizable($route)
    {
        if (is_null($route)) {
            return false;
        }

        $routeName = $route->getName();

        if (is_null($routeName)) {
            return false;
        }

        return true;
    }

    /**
     * Returns an array of URL of the supplied $routeName in
     * each $locales. If no route is defined for a locale, the
     * value is set to null.
     *
     * @param  string $routeName Non-localized route name
     * @param  array $locales    List of locales
     * @param  array $parameters Route parameters (default: [])
     * @param  boolean $absolute Return absolute route (default: true)
     * @return array             URLs for each locale (locale as
     *                           key)
     */
    public function getLocalizedRoutes($routeName, $locales, $parameters = [], $absolute = true)
    {
        $localizedRoutes = [];
        $routes = \Route::getRoutes();

        foreach ($locales as $localeCode) {
            $localizedRouteName = self::routeName($routeName, $localeCode);
            $routeData = null;

            if ($routes->hasNamedRoute($localizedRouteName)) {
                $routeData = route($localizedRouteName, $parameters, $absolute);
            }

            $localizedRoutes[$localeCode] = $routeData;
        }

        return $localizedRoutes;
    }

    /**
     * If the supplied locale is a valid one, return it, else return
     * config('locale.locale')
     *
     * @param  string $locale
     * @return  string
     */
    public function validateLocale($locale)
    {
        $allLocales = self::getAllLocales();
        $fallback = config('locale.locale');

        if (!in_array($locale, $allLocales)) {
            return $fallback;
        }

        return $locale;
    }

    /**
     * Returns all locales
     *
     * @return  array
     */
    public function getAllLocales()
    {
        return config('locale.locales');
    }

    /**
     * Returns screens locales
     *
     * @return  array
     */
    public function getScreensLocales()
    {
        return config('manivelle.screens.locales');
    }

    /**
     * Simple utility function that returns the $locale if set, else,
     * returns the local from $app.
     *
     * @param  string $locale
     * @return string
     */
    protected function getEffectiveLocale($locale = null)
    {
        if (is_null($locale)) {
            return $this->app->getLocale();
        }

        return $locale;
    }
}
