<?php

namespace Manivelle\Banq\Sources\Jobs;

use Manivelle\Models\Bubble;
use Manivelle\Models\Channel;
use Illuminate\Log\Writer;

class SyncBanqPhotosPage extends SyncBanqPhotos
{
    public $page;
    public $isLastPage;
    
    protected $shouldNeverSkip = true;
    
    public function __construct($page, $isLastPage = false)
    {
        $this->page = $page;
        $this->isLastPage = $isLastPage;
    }
    
    public function getSourceJobKey()
    {
        $key = parent::getSourceJobKey();
        return $key.'_page_'.$this->page;
    }
    
    public function sync()
    {
        $result = $this->syncPage($this->page);
    }
}
