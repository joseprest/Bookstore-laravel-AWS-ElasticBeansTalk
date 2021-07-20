<?php

namespace Manivelle\Cache;

use Manivelle\Services\GoogleAnalytics;

class ScreenStats extends Cache
{
    protected $key = 'screen_stats';
    protected $cacheOnCloud = true;
    
    protected $expires = 720;
    protected $localExpires = 720;
    
    public function getData()
    {
        $analytics = new GoogleAnalytics();
        
        $weekSummary = $analytics->getScreenSummary($this->item);
        $totalSummary = $analytics->getScreenSummary($this->item, '2005-01-01');
        
        $weekEvents = $analytics->getScreenEvents($this->item);
        $weekPageviews = $analytics->getScreenPageviews($this->item);
        
        return [
            'summary_week' => $weekSummary,
            'summary_total' => $totalSummary,
            'events_week' => $weekEvents,
            'pageviews_week' => $weekPageviews
        ];
    }
}
