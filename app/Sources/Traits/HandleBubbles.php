<?php

namespace Manivelle\Sources\Traits;

use Panneau;
use Cache;
use Log;
use Panneau\Exceptions\ResourceNotFoundException;
use Manivelle\Jobs\CreateImagesJob;
use Manivelle\Models\Bubble;
use Manivelle\Models\Channel;
use Carbon\Carbon;

trait HandleBubbles
{
    protected function getChannels()
    {
        $sourceType = $this->source->getSourceType();
        $channels = $sourceType->getChannels();
        return $channels;
    }

    protected function getBubbleType($data = [])
    {
        $sourceType = $this->source->getSourceType();
        $bubbleType = $sourceType->getBubbleType($data);
        return $bubbleType;
    }

    protected function getBubbleFromHandle($handle, $data = [])
    {
        $data = array_merge([
            'type' => $this->getBubbleType($data),
            'handle' => $handle
        ], $data);

        $resource = Panneau::resource('bubbles');

        try {
            $bubble = $resource->find([
                'type' => $data['type'],
                'handle' => $data['handle']
            ]);
            return $bubble;
        } catch (ResourceNotFoundException $e) {
        }

        return null;
    }

    protected function createBubble($handle, $fields = [], $data = [])
    {
        $handles = (array)$handle;

        $source = $this->getSource();

        $data = array_merge([
            'type' => $this->getBubbleType($data),
            'handle' => array_get($handles, '0', $handle),
            'fields' => $fields,
            'source_id' => $source ? $source->id:'0'
        ], $data);

        //Get bubble
        $bubble = null;
        $otherBubbles = [];
        $queryData = array_except($data, ['handle']);
        foreach ($handles as $handle) {
            $currentBubble = $this->getBubbleFromHandle($handle, $queryData);
            if ($currentBubble) {
                if (!$bubble) {
                    $bubble = $currentBubble;
                } else {
                    $otherBubbles[] = $currentBubble;
                }
            }
        }

        if (sizeof($otherBubbles) && method_exists($this, 'handleOtherBubbles')) {
            $this->handleOtherBubbles($otherBubbles, $bubble, $data);
        }

        $resource = Panneau::resource('bubbles');

        if ($bubble) {
            if (!$this->bubbleHasChanged($bubble, $fields)) {
                $key = $this->getSourceJobKey();
                $this->output->writeLn('<info>Job skipped:</info> '.$key);
                return false;
            }

            if (method_exists($this, 'updateDataFromBubble')) {
                $data = $this->updateDataFromBubble($bubble, $data);
            }

            // Notes: Possible performance issue
            if (!isset($data['updated_at'])) {
                $data['updated_at'] = Carbon::now()->toDateTimeString();
            }

            $resource->update($bubble->id, $data);
            $bubble->touch();
        } else {
            $bubble = $resource->store($data);
        }

        //Create pictures
        $this->createPictures($bubble);

        return $bubble;
    }

    protected function bubbleHasChanged($bubble, $fields)
    {
        /*if($this->bubbleExpiration !== -1)
        {
            $now = Carbon::now();
            $updatedDate = $bubble->updated_at;
            $hours = $now->diffInHours($updatedDate);
            if($hours < $this->bubbleExpiration)
            {
                Log::info('[SyncJob] Skip: Bubble is not expired '.$handle);
                return false;
            }
        }*/

        return true;
    }

    protected function createPictures($bubble)
    {
        $now = Carbon::now();
        $bubble->load('pictures');
        foreach ($bubble->pictures as $picture) {
            $hours = $now->diffInHours($picture->updated_at);
            if ($hours <= 0) {
                $this->dispatch(new CreateImagesJob($picture));
            }
        }
    }

    protected function addBubbleToChannels(Bubble $bubble)
    {
        $channels = $this->getChannels();

        foreach ($channels as $channel) {
            $channel->addBubble($bubble);
        }
    }
}
