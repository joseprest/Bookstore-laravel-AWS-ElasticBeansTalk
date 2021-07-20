<?php

namespace Manivelle\Banq\Sources\Jobs;

use Manivelle\Models\Bubble;
use Manivelle\Models\Channel;
use Illuminate\Log\Writer;

class SyncBanqBooksPage extends SyncBanqBooks
{
    public $year;
    public $page;
    public $isLastPage;
    
    protected $shouldNeverSkip = true;
    
    public function __construct($year, $page, $isLastPage = false)
    {
        $this->year = $year;
        $this->page = $page;
        $this->isLastPage = $isLastPage;
        
        $this->requestOptions['filters'] = [
            [
                'nom' => 'date_publication_f',
                'valeur' => $year
            ]
        ];
    }
    
    public function getSourceJobKey()
    {
        $key = parent::getSourceJobKey();
        return $key.'_'.$this->year.'_'.$this->page;
    }
    
    public function sync()
    {
        $result = $this->syncPage($this->page);
    }
}
