<?php

namespace Manivelle\Banq\Sources;

use Manivelle\Banq\Sources\Jobs\SyncBanqQuizz;

use Manivelle\Support\SourceType;
use Manivelle\Models\Source as SourceModel;

class BanqQuizz extends SourceType
{
    public function channelHandles()
    {
        return [
            'banq_quizz'
        ];
    }
    
    public function bubbleType()
    {
        return 'banq_question';
    }
    
    public function createSyncJob()
    {
        return new SyncBanqQuizz();
    }
}
