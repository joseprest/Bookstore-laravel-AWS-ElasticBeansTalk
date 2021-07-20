<?php

namespace Manivelle\Sources\MurMitoyen;

use Manivelle\Sources\MurMitoyen\Jobs\SyncMurMitoyen;

use Manivelle\Support\SourceType;
use Manivelle\Models\Source as SourceModel;

class MurMitoyen extends SourceType
{
    public function channelHandles()
    {
        return [
            'events'
        ];
    }
    
    public function bubbleType()
    {
        return 'event';
    }
    
    public function createSyncJob()
    {
        return new SyncMurMitoyen();
    }
}
