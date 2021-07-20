<?php namespace Manivelle\View\Composers;

use Asset;
use Auth;
use Panneau;
use Localizer;
use Lang;

class FooterAssetsComposer
{

    /**
     * Keys of localized strings to output
     * @var array
     */
    protected $localizedStringKeys = [
        'general',
        'layout',
        'screen',
        'team',
        'organisation',
        'bubble',
        'channel',
        'slideshow',
        'fields',
        'components',
        'invitation'
    ];
    
    public function compose($view)
    {
        $isOrganisationSite = $view->currentOrganisation ? true : false;
        $urls = null;
        $graphQlUrl = null;
        $localizedStrings = $this->getLocalizedStrings();

        // If we are on an 'organisation' sub-site
        if ($isOrganisationSite) {
            $organisationSlug = $view->currentOrganisation->slug;

            $graphQlUrl = Localizer::route('organisation.graphql.query', [$organisationSlug], false);
            
            $urls = [
                'organisation.home' => Localizer::route('organisation.home', [$organisationSlug]),
                'organisation.edit' => Localizer::route('organisation.edit', [$organisationSlug]),

                'organisation.screens.link' => Localizer::route('organisation.screens.link', [$organisationSlug]),
                'organisation.screens.unlink' => Localizer::route('organisation.screens.unlink', [$organisationSlug, ':screen_id']),
                'organisation.screens.show' => Localizer::route('organisation.screens.show', [$organisationSlug, ':screen_id']),
                'organisation.screens.channel' => Localizer::route('organisation.screens.channel', [$organisationSlug, ':screen_id', ':channel_id']),

                'organisation.team.create' => Localizer::route('organisation.team.create', [$organisationSlug]),
                'organisation.team.show' => Localizer::route('organisation.team.show', [$organisationSlug, ':user_id']),

                'organisation.bubbles.edit' => Localizer::route('organisation.bubbles.edit', [$organisationSlug, ':screen_id', ':channel_id', ':bubble_id']),
                'organisation.bubbles.destroy' => Localizer::route('organisation.bubbles.edit', [$organisationSlug, ':screen_id', ':channel_id', ':bubble_id']),
                'organisation.bubbles.store' => Localizer::route('organisation.bubbles.store', [$organisationSlug, ':screen_id', ':channel_id']),
                'organisation.bubbles.update' => Localizer::route('organisation.bubbles.update', [$organisationSlug, ':screen_id', ':channel_id', ':bubble_id'])
            ];
        } elseif (Auth::check()) {
            // If we are in the normal site and authenticated
            $graphQlUrl = Localizer::route('graphql.query', [], false);

            $urls = [
                // Used in account form
                'organisation.edit' => Localizer::route('organisation.edit', [':organisation'])
            ];
        }

        $view->urls = $urls;
        $view->graphQlUrl = $graphQlUrl;
        $view->localizedStrings = $localizedStrings;
    }

    /**
     * Returns the localized strings in the current locale for all the keys
     * returned by getLocalizedStringKeys().
     * @return array
     */
    protected function getLocalizedStrings()
    {
        $keys = $this->getLocalizedStringKeys();
        $strings = [];

        foreach ($keys as $key) {
            $strings[$key] = Lang::get($key);
        }

        return self::polyglotJsInterpolation($strings);
    }

    /**
     * Returns the keys of strings to localize
     * @return array
     */
    protected function getLocalizedStringKeys()
    {
        return $this->localizedStringKeys;
    }

    /**
     * Returns an array where Laravel variable interpolation :var are replaced
     * with Polyglot JS interpolation %{var}
     * @param  array $strings
     * @return array
     */
    protected static function polyglotJsInterpolation($strings)
    {
        array_walk_recursive($strings, function (&$string, $key) {
            if (strpos($string, ':') !== false) {
                $string = preg_replace('/:(\w+)/', '%{$1}', $string);
            }
        });

        return $strings;
    }
}
