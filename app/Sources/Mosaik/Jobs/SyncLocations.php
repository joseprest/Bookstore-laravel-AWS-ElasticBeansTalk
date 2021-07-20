<?php

namespace Manivelle\Sources\Mosaik\Jobs;

use Panneau;
use Exception;
use Panneau\Exceptions\ResourceNotFoundException;

use Manivelle\Models\Bubble;
use Manivelle\Models\Channel;

use Carbon\Carbon;
use Illuminate\Log\Writer;
use Manivelle\Sources\Job;
use GuzzleHttp\Client as HttpClient;
use ICal\ICal;

class SyncLocations extends Job
{
    protected $shouldNeverSkip = true;

    public function sync()
    {
        $locations = config('manivelle.sources.mosaik.locations');
        $lastIndex = sizeof($locations) - 1;
        $index = 0;
        foreach ($locations as $location) {
            $isLast = $index === $lastIndex;
            $job = new SyncLocation($location, $isLast);
            $this->dispatch($job);
            $index++;
        }
    }

    public function getSourceJobKey()
    {
        return 'mosaik_locations';
    }

    protected function getHandleFromItem($item)
    {
        return 'mosaik_location_'.$item['id'];
    }

    protected function getFieldsFromItem($item)
    {
        $fields = [
            'name' => $item['name'],
            'location' => array_except($item, ['phone', 'email']),
            'phone' => array_get($item, 'phone', null),
            'email' => array_get($item, 'email', null),
        ];
        return $fields;
    }

    protected function bubbleHasChanged($bubble, $fields)
    {
        return true;
    }

    protected function sanitize($text)
    {
        return html_entity_decode(strip_tags(preg_replace('/\<br\s+?\/?\>/', "\n", $text)), ENT_QUOTES, 'utf-8');
    }
}
