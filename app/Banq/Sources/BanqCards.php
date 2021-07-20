<?php

namespace Manivelle\Banq\Sources;

use Manivelle\Banq\Sources\Jobs\SyncBanqCards;

use Manivelle\Support\SourceType;
use Manivelle\Models\Source as SourceModel;

class BanqCards extends SourceType
{
    public function channelHandles()
    {
        return [
            'banq_cards'
        ];
    }
    
    public function bubbleType()
    {
        return 'banq_card';
    }
    
    public function createSyncJob()
    {
        return new SyncBanqCards();
    }
}
