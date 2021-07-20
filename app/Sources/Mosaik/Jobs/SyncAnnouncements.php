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

class SyncAnnouncements extends Job
{
    protected $shouldNeverSkip = true;

    public function sync()
    {
        $items = $this->loadData();
        $lastIndex = sizeof($items) - 1;
        $index = 0;
        foreach ($items as $item) {
            $isLast = $index === $lastIndex;
            $job = new SyncAnnouncement($item, $isLast);
            $this->dispatch($job);
            $index++;
        }
    }

    public function getSourceJobKey()
    {
        return 'mosaik_announcements';
    }

    protected function loadData()
    {
        $url = config('manivelle.sources.mosaik.announcements_endpoint');
        $client = new HttpClient();
        $response = $client->request('GET', $url);

        $status = $response->getStatusCode();
        if ($status !== 200) {
            throw new \Exception('[Source mosaik_announcements] Status code '.$status.' for '.$url);
        }

        $data = json_decode((string) $response->getBody(), true);
        return $data;
    }

    protected function getHandleFromItem($item)
    {
        return 'mosaik_announcement_'.$item['id'];
    }

    protected function getFieldsFromItem($item)
    {
        $fields = [
            'title' => $item['title'],
            'description' => $item['description'],
            'link' => $item['url'],
            'picture' => $item['img'],
            'published_at' => Carbon::createFromFormat('Y-m-d', $item['date_post'])->toDateTimeString(),
        ];
        return $fields;
    }

    protected function bubbleHasChanged($bubble, $fields)
    {
        $currentUpdatedDate = $fields['published_at'] ? Carbon::parse($fields['published_at']):null;
        $bubbleUpdateDate = $bubble->fields->published_at ? Carbon::parse($bubble->fields->published_at):null;
        if ((!$currentUpdatedDate && $bubbleUpdateDate) ||
            (!$bubbleUpdateDate && $currentUpdatedDate) ||
            $currentUpdatedDate->gt($bubbleUpdateDate)
        ) {
            return true;
        }

        $changeFields = ['title', 'description', 'link'];
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
        return html_entity_decode(strip_tags(preg_replace('/\<br\s+?\/?\>/', "\n", $text)), ENT_QUOTES, 'utf-8');
    }
}
