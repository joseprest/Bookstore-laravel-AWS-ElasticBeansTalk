<?php

namespace Manivelle\Banq\Sources\Jobs;

use Manivelle\Models\Bubble;
use Manivelle\Models\Channel;
use Illuminate\Log\Writer;

class SyncBanqBooksYear extends SyncBanqBooks
{
    public $year;
    public $isLastYear;
    
    protected $shouldNeverSkip = true;
    
    public function __construct($year, $isLastYear = false)
    {
        $this->year = $year;
        $this->isLastYear = $isLastYear;
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
        return $key.'_'.$this->year;
    }
    
    public function sync()
    {
        $result = $this->syncPages();
    }
}
