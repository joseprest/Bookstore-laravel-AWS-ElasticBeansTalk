<?php namespace Manivelle\View\Composers\Organisation\Screens\Tabs;

use View;
use Manivelle\User;
use Panneau;
use Route;
use DB;
use Str;

use Illuminate\Http\Request;
use Manivelle\Models\ScreenPivot;
use Manivelle;
use DateInterval;

class StatsComposer
{
    protected $request;
    protected $organisation;
    
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->organisation = $request->organisation;
    }
    
    public function compose($view)
    {
        $organisation = $this->organisation;
        $screen = $view->item instanceof ScreenPivot ? $view->item->screen:$view->item;
        
        $view->screen = $screen;
        
        $stats = $this->humanizeStats($screen->getStats());
        $view->stats = $stats;
        $view->weekDuration = $this->formatDuration($stats['summary_week']['duration']);
        $view->totalDuration = $this->formatDuration($stats['summary_total']['duration']);
    }
    
    protected function formatDuration($duration)
    {
        return gmdate('i:s', $duration);
    }

    /**
     * Takes the raw stats and returns an array with more friendly and 
     * localized events and page views names
     * @param  array $stats
     * @return array
     */
    protected function humanizeStats($stats)
    {
        $humanizedStats = [];

        foreach ($stats as $type => $typeStats) {
            if ($type == 'events_week') {
                $humanizedStats[$type] = $this->humanizeEvents($typeStats);
            } elseif ($type == 'pageviews_week') {
                $humanizedStats[$type] = $this->humanizePageViews($typeStats);
            } else {
                $humanizedStats[$type] = $typeStats;
            }
        }

        return $humanizedStats;
    }

    /**
     * From a page views stat array, returns the page views with more friendly
     * and localized names, also groups channel page views together.
     *
     * So from this array : [
     *   '/screen/58/channel/photos-darchives/' => 15,
     *   '/screen/58/channel/photos-darchives/bubbles' => 25,
     *   '/screen/58/channel/photos-darchives/tabs/annees' => 1,
     *   '/screen/58/menu' => 13
     * ]
     *
     * returns this array : [
     *     'photos-darchives' => [
     *         'value' => 41,
     *         'children' => [
     *             'Chaîne' => 15,
     *             'Visionneuse de contenu' => 25,
     *             'Onglet "annees"' => 1
     *         ]
     *     ]
     *     'Menu' => 13
     * ]
     * 
     * @param  array $stats
     * @return array
     */
    protected function humanizePageViews($stats)
    {
        $humanizedStats = [];

        foreach ($stats as $rawKey => $value) {
            $key = preg_replace('~^/screen/[0-9]+/~', '', $rawKey);
            $label = $key;
            $isChannel = strpos($key, 'channel/') === 0
                && $key != 'channel/';

            // If the page view is for a channel (starts with channel/)
            if ($isChannel) {
                $isTab = false;
                $channelParts = [];
                preg_match('~^channel/([^/]+)(/(.*))?$~', $key, $channelParts);
                $channelSlug = $channelParts[1];
                $channelName = trans('stats.channels.' . $channelSlug);
                $transKey = 'channel';
                $transVariables = [];

                // Get or create the stat 'group' for this channel
                $statGroup = array_get($humanizedStats, $channelName, [
                    'value' => 0,
                    'children' => []
                ]);

                // If the page view has a sub part (ex: bubbles in
                // channel/bubbles or tabs/annees in channel/tabs/annees)
                if (isset($channelParts[3]) && $channelParts[3]) {
                    $subPart = $channelParts[3];
                    $isTab = strpos($subPart, 'tabs/') === 0;

                    if ($isTab) {
                        $tabSlug = substr($subPart, strlen('tabs/'));
                        $transTabName = 'stats.tabs.' . $tabSlug;
                        if (($tabName = trans($transTabName)) == $transTabName) {
                            $tabName = $tabSlug;
                        }
                        $transKey = 'channel_tab';
                        $transVariables['tab'] = $tabName;
                    } else {
                        $transKey = 'channel_' . $subPart;
                    }
                }

                $label = trans('stats.pageviews.' . $transKey, $transVariables);

                $statGroup['value'] += intval($value);
                $statGroup['children'][$label] = $value;
                $humanizedStats[$channelName] = $statGroup;

            } else {
                // If here, the page view is not for a channel (ex: menu) and
                // we just try to find a localization
                $label = trans('stats.pageviews.' . $key);
                $humanizedStats[$label] = $value;
            }
        }

        return $humanizedStats;
    }

    /**
     * From an events stat array, returns the events with localized names.
     *
     * So from this array : [
     *   'Channel bubbles slide change' => 15,
     *   'Send bubble email' => 25
     * ]
     *
     * returns this array : [
     *   'Changement de contenu (Chaîne)' => 15,
     *   'Envoi d\'un contenu par courriel' => 25
     * ]
     * 
     * @param  array $stats
     * @return array
     */
    protected function humanizeEvents($stats)
    {
        $humanizedStats = [];

        foreach ($stats as $key => $value) {
            $label = trans('stats.events.' . Str::slug($key));
            $humanizedStats[$label] = $value;
        }

        return $humanizedStats;
    }
}
