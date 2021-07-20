<?php

namespace Manivelle\Jobs;

use Manivelle;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Log\Writer;
use Illuminate\Foundation\Bus\DispatchesJobs;

use Manivelle\Models\SourceSync;
use Carbon\Carbon;

class SourceStartSync extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels, DispatchesJobs;
    
    public $sync;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(SourceSync $sync)
    {
        $this->sync = $sync;
    }
    
    public function handle()
    {
        $this->sync->start();
    
        $sync = $this->sync;
        $source = $this->sync->source;
        $sourceType = $source->getSourceType();
        $job = $sourceType->syncJob($source, $sync);
        $this->dispatch($job);
    }
}
