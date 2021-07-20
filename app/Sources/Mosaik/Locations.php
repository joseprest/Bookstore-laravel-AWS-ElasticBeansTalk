<?php

namespace Manivelle\Sources\Mosaik;

use Manivelle\Sources\Mosaik\Jobs\SyncLocations;

use Manivelle\Support\SourceType;
use Manivelle\Models\Source as SourceModel;

class Locations extends SourceType
{
    public function channelHandles()
    {
        return [
            'locations_vaudreuil'
        ];
    }

    public function bubbleType()
    {
        return 'location';
    }

    public function createSyncJob()
    {
        return new SyncLocations();
    }
}
