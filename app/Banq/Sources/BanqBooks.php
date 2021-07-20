<?php

namespace Manivelle\Banq\Sources;

use Manivelle\Banq\Sources\Jobs\SyncBanqBooks;

use Manivelle\Support\SourceType;
use Manivelle\Models\Source as SourceModel;

class BanqBooks extends SourceType
{
    public function channelHandles()
    {
        return [
            'banq_books'
        ];
    }
    
    public function bubbleType()
    {
        return 'banq_book';
    }
    
    public function createSyncJob()
    {
        return new SyncBanqBooks();
    }
}
