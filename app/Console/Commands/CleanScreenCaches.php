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

class CleanScreenCaches extends Command
{
    use DispatchesJobs;
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manivelle:clean:screen_caches {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean screen caches';

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
        
        if ($force || $this->confirm('Do you wish delete '.$count.' screen cache(s)? [y|N]')) {
            $count = $this->newQuery()->delete();
            $this->line('<info>Deleted:</info> '.$count.' screen cache(s)');
        }
    }
    
    protected function newQuery()
    {
        return DB::table('screens_caches');
    }
}
