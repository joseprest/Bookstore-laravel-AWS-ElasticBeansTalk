<?php

namespace Manivelle\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Foundation\Bus\DispatchesJobs;

use Manivelle\Models\Source;
use Manivelle\Models\SourceSync;

use Carbon\Carbon;

use Manivelle\Jobs\SourceStartSync;

class SourcesFinish extends Command
{
    use DispatchesJobs;
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manivelle:sources:finish {handle} {--type=} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Finish sources syncing';

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
            $sync = $source->getCurrentSync();
            if ($sync) {
                $sync->finish();
                $deletedJobs = $sync->deleteUnreservedJobs();
                $this->line(
                    '<comment>Finished:</comment> Source '.$source->name.
                    ' will finish. '.$deletedJobs.' job(s) deleted'
                );
            } else {
                $this->line('<comment>Skipped:</comment> Source '.$source->name.' is not running.');
            }
        }
    }
}
