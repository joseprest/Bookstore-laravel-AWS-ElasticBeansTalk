<?php

namespace Manivelle\Sources\Cairn;

use Manivelle\Sources\Cairn\Jobs\SyncCairn;

use Manivelle\Support\SourceType;
use Manivelle\Models\Source;
use Manivelle\Models\SourceSync;

class Cairn extends SourceType
{
    public function channelHandles()
    {
        return [
            'publications'
        ];
    }

    public function bubbleType()
    {
        return 'publication';
    }

    public function createSyncJob()
    {
        return new SyncCairn();
    }
}
