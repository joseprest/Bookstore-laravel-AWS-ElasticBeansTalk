<?php

namespace Manivelle\Sources\MurMitoyen\Jobs;

use Manivelle\Support\SyncJob;

use Panneau;
use Exception;
use Log;
use Panneau\Exceptions\ResourceNotFoundException;

use Manivelle\Models\Bubble;
use Manivelle\Models\Channel;

use Carbon\Carbon;
use Illuminate\Log\Writer;

class SyncEvent extends SyncMurMitoyen
{
    public $id;
    public $isLast;

    protected $shouldNeverSkip = false;

    public function __construct($id, $isLast = false)
    {
        $this->id = $id;
        $this->isLast = $isLast;
    }

    public function getSourceJobKey()
    {
        $key = parent::getSourceJobKey();
        return $key.'_item_'.$this->id;
    }

    public function sync()
    {
        $eventData = $this->loadEvent($this->id);
        if (!$eventData) {
            throw new Exception('[Source murmitoyen] Event not loaded '.$this->id.'.');
        }

        try {
            $handle = $this->getHandleFromData($eventData);
            $bubble = $this->getBubbleFromHandle($handle);
            $fields = $this->getFieldsFromData($eventData);
            if (!$fields) {
                throw new Exception('Skip');
            }

            $currentUpdatedDate = $fields['updated_at'] ? Carbon::parse($fields['updated_at']):null;
            $bubbleUpdateDate = $bubble && $bubble->fields->updated_at ? Carbon::parse($bubble->fields->updated_at):null;
            if ($currentUpdatedDate && $bubbleUpdateDate && $currentUpdatedDate->lte($bubbleUpdateDate)) {
                throw new Exception('Skip');
            }

            $bubble = $this->createBubble($handle, $fields);

            if ($bubble === false) {
                throw new Exception('Skip');
            }

            $this->addBubbleToChannels($bubble);
        } catch (Exception $e) {
            $message = $e->getMessage();
            if ($message !== 'Skip') {
                Log::error($e);
            }
        }
    }
}
