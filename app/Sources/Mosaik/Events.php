<?php

namespace Manivelle\Sources\Mosaik;

use Manivelle\Sources\Mosaik\Jobs\SyncEvents;

use Manivelle\Support\SourceType;
use Manivelle\Models\Source as SourceModel;

class Events extends SourceType
{
    public function channelHandles()
    {
        return [
            'events_vaudreuil'
        ];
    }

    public function bubbleType()
    {
        return 'event';
    }

    public function createSyncJob()
    {
        return new SyncEvents();
    }
}
