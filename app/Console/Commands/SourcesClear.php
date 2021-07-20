<?php

namespace Manivelle\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Foundation\Bus\DispatchesJobs;

use Manivelle\Models\Source;
use Manivelle\Models\SourceSync;

use Carbon\Carbon;

use Manivelle\Jobs\SourceStartSync;

class SourcesClear extends Command
{
    use DispatchesJobs;
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manivelle:sources:clear {handle} {--type=} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear sources syncing';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $manivelle = app('manivelle');
        
        $query = Source::query()->with('syncs');
        
        if ($this->argument('handle') !== 'all') {
            $query->where('handle', $this->argument('handle'));
        }
        
        if ($this->option('type')) {
            $query->where('type', $this->option('type'));
        }
        
        $force = $this->option('force');
        
        $syncs = [];
        $sources = $query->get();
        foreach ($sources as $source) {
            foreach ($source->syncs as $sync) {
                $syncs[] = $sync;
            }
        }
        
        if (!$force && !$this->confirm('Do you wish to clear '.sizeof($syncs).' syncing? [y|N]')) {
            return;
        }
        
        foreach ($syncs as $sync) {
            $sync->stop();
            $sync->deleteUnreservedJobs();
            $sync->delete();
        }
    }
}
