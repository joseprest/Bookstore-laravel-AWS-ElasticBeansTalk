<?php

namespace Manivelle\Sources\Mosaik;

use Manivelle\Sources\Mosaik\Jobs\SyncAnnouncements;

use Manivelle\Support\SourceType;
use Manivelle\Models\Source as SourceModel;

class Announcements extends SourceType
{
    public function channelHandles()
    {
        return [
            'announcements_vaudreuil'
        ];
    }

    public function bubbleType()
    {
        return 'announcement';
    }

    public function createSyncJob()
    {
        return new SyncAnnouncements();
    }
}
