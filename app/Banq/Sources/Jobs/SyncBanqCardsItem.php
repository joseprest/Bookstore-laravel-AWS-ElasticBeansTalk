<?php

namespace Manivelle\Banq\Sources\Jobs;

use Manivelle\Models\Bubble;
use Manivelle\Models\Channel;
use Illuminate\Log\Writer;

class SyncBanqCardsItem extends SyncBanqPhotosItem
{
    public $requestOptions = [
        'type' => 'Cartes postales',
        'page' => 1,
        'nbr' => 100,
        'texte' => ''
    ];

    protected $shouldNeverSkip = false;

    public function getSourceJobKey()
    {
        $id = array_get($this->item, 'id', '-');
        return 'banq_cards_card_'.$id;
    }
}
