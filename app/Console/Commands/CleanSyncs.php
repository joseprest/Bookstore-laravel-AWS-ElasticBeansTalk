<?php

namespace Manivelle\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

use Manivelle\Models\Bubble;
use Manivelle\Contracts\Bubbles\Cleanable;
use Manivelle\Jobs\CleanBubble;
use Manivelle\Jobs\CreateCache;

use Log;
use Artisan;
use DB;
use Carbon\Carbon;

class CleanSyncs extends Command
{
    use DispatchesJobs;
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manivelle:clean:syncs {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean sources syncs';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $force = $this->option('force');
        
        $count = $this->newQuery()->count();
        
        if (!$count) {
            return;
        }
        
        if ($force || $this->confirm('Do you wish delete '.$count.' sources sync(s)? [y|N]')) {
            $count = $this->newQuery()->delete();
            $this->line('<info>Deleted:</info> '.$count.' sources sync(s)');
        }
    }
    
    protected function newQuery()
    {
        return DB::table('sources_syncs')
            ->where('finished', 1)
            ->where('finished_at', '<=', Carbon::now()->subWeeks(2));
    }
}
