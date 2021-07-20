<?php

namespace Manivelle\Sources\QuizzCSV;

use Manivelle\Sources\QuizzCSV\Jobs\SyncQuizzCSV;

use Manivelle\Support\SourceType;
use Manivelle\Models\Source;
use Manivelle\Models\SourceSync;

class QuizzCSV extends SourceType
{
    public function channelHandles()
    {
        return [
            'quizz'
        ];
    }

    public function bubbleType()
    {
        return 'quizz_question';
    }

    public function createSyncJob()
    {
        $path = storage_path('sources/quizz/data2.csv');
        $imagesFolder = storage_path('sources/quizz/images');
        return new SyncQuizzCSV($path, $imagesFolder);
    }
}
