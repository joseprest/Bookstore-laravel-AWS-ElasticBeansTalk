<?php

namespace Manivelle\Sources\Mosaik\Jobs;

use Manivelle\Support\SyncJob;

use Panneau;
use Exception;
use Log;
use Panneau\Exceptions\ResourceNotFoundException;

use Manivelle\Models\Bubble;
use Manivelle\Models\Channel;

use Carbon\Carbon;
use Illuminate\Log\Writer;

class SyncAnnouncement extends SyncAnnouncements
{
    public $item;
    public $isLast;

    protected $shouldNeverSkip = false;

    public function __construct($item, $isLast = false)
    {
        $this->item = $item;
        $this->isLast = $isLast;
    }

    public function getSourceJobKey()
    {
        $key = parent::getSourceJobKey();
        return $key.'_item_'.$this->item['id'];
    }

    public function sync()
    {
        try {
            $handle = $this->getHandleFromItem($this->item);
            $bubble = $this->getBubbleFromHandle($handle);
            $fields = $this->getFieldsFromItem($this->item);
            if (!$fields) {
                throw new Exception('Skip');
            }

            $currentUpdatedDate = $fields['published_at'] ? Carbon::parse($fields['published_at']):null;
            $bubbleUpdateDate = $bubble && $bubble->fields->published_at ? Carbon::parse($bubble->fields->published_at):null;
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
