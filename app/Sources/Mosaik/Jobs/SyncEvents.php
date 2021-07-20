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

class SyncEvents extends Job
{
    protected $shouldNeverSkip = true;

    public function sync()
    {
        $items = $this->loadData();
        $lastIndex = sizeof($items) - 1;
        $index = 0;
        $now = Carbon::now()->startOfDay();
        foreach ($items as $item) {
            $startDate = $this->parseDate($item['date_start']);
            $endDate = $this->parseDate($item['date_end']);
            if ($startDate->gte($now) || (!is_null($endDate) && $endDate->gte($now))) {
                $isLast = $index === $lastIndex;
                $job = new SyncEvent($item, $isLast);
                $this->dispatch($job);
            }
            $index++;
        }
    }

    protected function parseDate($date)
    {
        return !empty($date) ? Carbon::createFromFormat(
            'Y-m-d H:i:s',
            $date,
            config('app.timezone')
        ) : null;
    }

    public function getSourceJobKey()
    {
        return 'mosaik_events';
    }

    protected function loadData()
    {
        $url = config('manivelle.sources.mosaik.events_endpoint');
        $client = new HttpClient();
        $response = $client->request('GET', $url);

        $status = $response->getStatusCode();
        if ($status !== 200) {
            throw new \Exception('[Source mosaik_events] Status code ' . $status . ' for ' . $url);
        }

        $data = json_decode((string) $response->getBody(), true);
        return $data;
    }

    protected function getHandleFromItem($item)
    {
        return 'mosaik_event_' . $item['id'];
    }

    protected function getFieldsFromItem($item)
    {
        $endDate = $this->parseDate($item['date_end']);
        $fields = [
            'title' => $item['title'],
            'description' => $item['description'],
            'link' => $item['url'],
            'picture' => $item['img'],
            'date' => [
                'start' => $this->parseDate($item['date_start'])->toDateTimeString(),
                'end' => !is_null($endDate) ? $endDate->toDateTimeString() : null,
            ],
            'venue' => $this->getVenue($item['loc_id']),
            'updated_at' => Carbon::createFromFormat(
                'Ymd\THisZ',
                $item['date_stamp'],
                'GMT'
            )->setTimezone(config('app.timezone'))->toDateTimeString(),
        ];
        return $fields;
    }

    protected function getVenue($id)
    {
        $locations = config('manivelle.sources.mosaik.locations');
        $location = array_first($locations, function ($index, $item) use ($id) {
            return (string)$item['id'] === (string)$id;
        });
        return isset($location)
            ? array_merge($location, [
                'id' => 'mozaik_' . $location['id'],
                'region' => 'Québec',
                'country' => 'Canada',
            ])
            : null;
    }

    protected function bubbleHasChanged($bubble, $fields)
    {
        $currentUpdatedDate = $fields['updated_at'] ? Carbon::parse($fields['updated_at']) : null;
        $bubbleUpdateDate = $bubble->fields->updated_at
            ? Carbon::parse($bubble->fields->updated_at)
            : null;
        // prettier-ignore
        if ((!$currentUpdatedDate && $bubbleUpdateDate) ||
            (!$bubbleUpdateDate && $currentUpdatedDate) ||
            $currentUpdatedDate->gt($bubbleUpdateDate)
        ) {
            return true;
        }

        $changeFields = ['title', 'description', 'link', 'date.start', 'date.end'];
        foreach ($changeFields as $field) {
            $currentValue = data_get($bubble->fields, $field);
            $newValue = data_get($fields, $field);
            if ($currentValue !== $newValue) {
                return true;
            }
        }

        return false;
    }

    protected function sanitize($text)
    {
        return html_entity_decode(
            strip_tags(preg_replace('/\<br\s+?\/?\>/', "\n", $text)),
            ENT_QUOTES,
            'utf-8'
        );
    }
}
