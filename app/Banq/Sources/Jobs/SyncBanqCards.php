<?php

namespace Manivelle\Banq\Sources\Jobs;

class SyncBanqCards extends SyncBanqPhotos
{
    public $requestOptions = [
        'type' => 'Cartes postales',
        'page' => 1,
        'nbr' => 100,
        'texte' => ''
    ];

    protected $shouldNeverSkip = true;

    public function sync()
    {
        if (!config('manivelle.banq.cards.disable_source')) {
            $result = $this->syncPages();
        }
    }

    protected function createPageJob($page, $isLastPage = false)
    {
        return new SyncBanqCardsPage($page, $isLastPage);
    }

    public function getSourceJobKey()
    {
        return 'banq_cards';
    }
}
