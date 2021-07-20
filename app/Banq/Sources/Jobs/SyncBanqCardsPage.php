<?php

namespace Manivelle\Banq\Sources\Jobs;

use Manivelle\Models\Bubble;
use Manivelle\Models\Channel;
use Illuminate\Log\Writer;

class SyncBanqCardsPage extends SyncBanqPhotosPage
{
    public $requestOptions = [
        'type' => 'Cartes postales',
        'page' => 1,
        'nbr' => 100,
        'texte' => ''
    ];
    
    protected $shouldNeverSkip = true;
    
    public function getSourceJobKey()
    {
        return 'banq_cards_page_'.$this->page;
    }

    protected function createItemJob($item, $isLast = false)
    {
        return new SyncBanqCardsItem($item, $isLast);
    }
}
