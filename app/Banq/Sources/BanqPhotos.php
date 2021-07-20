<?php

namespace Manivelle\Banq\Sources;

use Manivelle\Banq\Sources\Jobs\SyncBanqPhotos;

use Manivelle\Support\SourceType;
use Manivelle\Models\Source as SourceModel;

class BanqPhotos extends SourceType
{
    public function channelHandles()
    {
        return [
            'banq_photos'
        ];
    }
    
    public function bubbleType()
    {
        return 'banq_photo';
    }
    
    public function createSyncJob()
    {
        return new SyncBanqPhotos();
    }
}
