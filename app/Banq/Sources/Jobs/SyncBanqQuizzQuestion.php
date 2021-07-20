<?php

namespace Manivelle\Banq\Sources\Jobs;

use Panneau;
use Panneau\Exceptions\ResourceNotFoundException;

use Manivelle\Support\SyncJob;

use Manivelle\Models\Bubble;
use Manivelle\Models\Channel;
use Illuminate\Log\Writer;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException;
use Exception;

use Imagick;

class SyncBanqQuizzQuestion extends SyncBanqQuizz
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
        $id = array_get($this->item, 'id_faq');
        return $key.'_question_'.$id;
    }
    
    public function sync()
    {
        $handle = $this->getHandleFromItem($this->item);
        
        $image = 'http://www.banq.qc.ca'.trim(array_get($this->item, 'url_alt', ''));
        if (!$this->imageIsValid($image)) {
            return;
        }
        
        $fields = $this->getFieldsFromItem($this->item);
        
        $bubble = $this->createBubble($handle, $fields);
        
        if ($bubble) {
            $this->addBubbleToChannels($bubble);
        }
    }
}
