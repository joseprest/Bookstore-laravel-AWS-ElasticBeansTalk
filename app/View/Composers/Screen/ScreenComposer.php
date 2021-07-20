<?php namespace Manivelle\View\Composers\Screen;

use Auth;
use Lang;
use App;
use Manivelle\User;
use Panneau;
use Illuminate\Http\Request;

use Manivelle\Models\Channel;

class ScreenComposer
{
    public function compose($view)
    {
        $screen = $view->screen;
        $base = route('screen.home', [$screen->uuid]);
        $view->screenUrl = str_replace($base, '', route('screen.data.screen', [$screen->uuid]));
        $view->dataUrls = [
            'bubbles' => str_replace($base, '', route('screen.data.bubbles', [$screen->uuid])),
            'channels' => str_replace($base, '', route('screen.data.channels', [$screen->uuid])),
            'timeline' => str_replace($base, '', route('screen.data.timeline', [$screen->uuid])),
            'bubblesPage' => str_replace($base, '', route('screen.data.bubble_page_count', [$screen->uuid, config('manivelle.screens.bubbles_per_page'), ':page']))
        ];

        $view->isBanq = isset($view->organisation) && $view->organisation->slug === 'banq' ? true:false;

        $settings = $screen->settings;
        $keyboardLayout = array_get($settings, 'keyboardAlternativeLayout', null);
        $headerTitle = array_get($settings, 'headerTitle', null);
        $view->manivelleProps = [
            'hasHeader' => (bool)array_get($settings, 'hideHeader', false) ? false:true,
            'menuWithSummary' => (bool)array_get($settings, 'hideMenuSummary', false) ? false:true,
            'hasManivelle' => (bool)array_get($settings, 'disableManivelle', false) ? false:true,
            'keyboardAlternativeLayout' => !empty($keyboardLayout) ? $keyboardLayout : null,
            'fontFamily' => $view->isBanq ? 'simplon':null,
            'headerTitle' => !empty($headerTitle) ? $headerTitle : null,
            'channelsMenuAlwaysVisible' => (bool)array_get($settings, 'channelsMenuAlwaysVisible', false),
        ];

        //Get starting view
        $disableSlideshow = (bool)array_get($settings, 'disableSlideshow', false);
        $startView = array_get($settings, 'startView', 'default');
        $startView = empty($startView) || $startView === 'default' ? null:explode(':', $startView);
        $startViewProps = null;
        // if ($startView && sizeof($startView)) {
        //     $type = $startView[0];
        //     if ($type === 'channel') {
        //         $channelId = $startView[1];
        //         $channel = Channel::find($channelId);
        //         $channelViews = $channel->getChannelType()->getViews();
        //         $viewKey = array_get($startView, '2');
        //         $channelView = array_first($channelViews, function ($key, $item) use ($viewKey) {
        //             return $item['key'] === $viewKey;
        //         });
        //         if ($channelView) {
        //             $startViewProps = array_merge([
        //                 'channelId' => $channelId,
        //                 'view' => 'channel:main'
        //             ], array_get($channelView, 'props', []));
        //         }
        //     }
        // }
        if ($disableSlideshow) {
            $startViewProps = [
                'view' => 'menu'
            ];
        }
        $view->startViewProps = $startViewProps;

        $view->theme = array_get($settings, 'theme', null);
        $view->locale = array_get($settings, 'locale', App::getLocale());
        $phrases = $this->getPhrases($screen->getSupportedLocales());
        if (count($phrases) && isset($phrases[$view->locale])) {
            $view->phrases = $phrases;
        } else {
            $view->phrases = null;
        }
    }

    public function getPhrases($locales)
    {
        $phrases = [];

        foreach ($locales as $locale) {
            if (Lang::hasForLocale('interface', $locale)) {
                $phrases[$locale] = Lang::get('interface', [], $locale);
            }
        }

        return sizeof($phrases) ? $phrases : null;
    }
}
