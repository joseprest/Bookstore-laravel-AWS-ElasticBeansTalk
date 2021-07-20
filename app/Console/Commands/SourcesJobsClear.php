<?php

namespace Manivelle\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Foundation\Bus\DispatchesJobs;

use Manivelle\Models\Source;
use Manivelle\Models\SourceSync;

use Carbon\Carbon;

use Manivelle\Jobs\SourceStartSync;

class SourcesJobsClear extends Command
{
    use DispatchesJobs;
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manivelle:sources:jobs:clear {handle} {--type=} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear sources jobs';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $manivelle = app('manivelle');
        
        $query = Source::query();
        
        if ($this->argument('handle') !== 'all') {
            $query->where('handle', $this->argument('handle'));
        }
        
        if ($this->option('type')) {
            $query->where('type', $this->option('type'));
        }
        
        $force = $this->option('force');
        $sources = $query->get();
        foreach ($sources as $source) {
            $count = $source->jobs()->delete();
            $this->line('<info>Cleared:</info> '.$count.' jobs on '.$source->name);
        }
    }
}
