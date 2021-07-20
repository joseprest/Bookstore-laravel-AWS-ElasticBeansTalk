<?php

namespace Manivelle\Sources\PretNumerique;

use Manivelle\Sources\PretNumerique\Jobs\SyncPretNumerique;

use Manivelle\Support\SourceType;
use Manivelle\Models\Source;
use Manivelle\Models\SourceSync;

class PretNumerique extends SourceType
{
    public function channelHandles()
    {
        return [
            'books'
        ];
    }

    public function bubbleType()
    {
        return 'book';
    }

    public function createSyncJob()
    {
        return new SyncPretNumerique($this->getLibraries());
    }

    public function getLibraries()
    {
        $fromDatabase = config('manivelle.sources.pretnumerique.libraries_from_database', false);
        $defaultLibraries = config('manivelle.sources.pretnumerique.libraries', []);
        return $fromDatabase && !is_null($this->source) && is_array($this->source->settings) ?
            array_get($this->source->settings, 'libraries', $defaultLibraries) : $defaultLibraries;
    }
}
