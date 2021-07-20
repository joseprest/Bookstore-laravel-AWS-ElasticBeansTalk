<?php

namespace Manivelle\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Foundation\Bus\DispatchesJobs;

use Manivelle\Models\Source;
use Manivelle\Models\SourceSync;

use Carbon\Carbon;

use Manivelle\Jobs\SourceStartSync;

class SourcesStart extends Command
{
    use DispatchesJobs;
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manivelle:sources:start {handle?} {--type=} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start sources syncing';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $manivelle = app('manivelle');
        
        $query = Source::query();
        
        if ($this->argument('handle')) {
            $query->where('handle', $this->argument('handle'));
        }
        
        if ($this->option('type')) {
            $query->where('type', $this->option('type'));
        }
        
        $force = $this->option('force');
        $sources = $query->get();
        foreach ($sources as $source) {
            if (!$source->isSyncing() || $force) {
                $sync = $source->getCurrentSync();
                if (!$sync) {
                    $sync = new SourceSync();
                }
                $source->syncs()->save($sync);
                $this->dispatchNow(new SourceStartSync($sync));
                
                $this->line('<comment>Started:</comment> Source '.$source->name.'.');
            } else {
                $this->line('<comment>Skipped:</comment> Source '.$source->name.' is already syncing.');
            }
        }
    }
}
