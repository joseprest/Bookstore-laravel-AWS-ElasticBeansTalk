<?php

namespace Manivelle\Banq\Sources\Jobs;

use Manivelle\Models\Bubble;
use Manivelle\Models\Channel;
use Illuminate\Log\Writer;

use Log;
use App;

class SyncBanqBooksItem extends SyncBanqBooks
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
        $isbn = array_get($this->item, 'isbn', '-');
        $id = array_get($this->item, 'id', '-');
        return $key.'_book_'.$isbn.'_'.$id;
    }

    public function sync()
    {
        $handle = $this->getHandleFromItem($this->item);
        $fields = $this->getFieldsFromItem($this->item);

        //Check image
        $imagette = array_get($this->item, 'imagette', '');
        if (empty($imagette) ||
            $imagette === 'http://iris.banq.qc.ca/Portal3/IMG/MAT/mbook_f.gif' ||
            (App::environment() !== 'local' && !$this->imageIsValid($imagette))
        ) {
            return;
        }

        if ($fields) {
            $bubble = $this->createBubble($handle, $fields);

            if ($bubble) {
                $this->addBubbleToChannels($bubble);
            }
        }
    }
}
