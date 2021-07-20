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

class CleanPlaylists extends Command
{
    use DispatchesJobs;
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manivelle:clean:playlists {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean playlists';

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
        
        if ($force || $this->confirm('Do you wish delete '.$count.' playlist item(s)? [y|N]')) {
            $count = $this->newQuery()->delete();
            $this->line('<info>Deleted:</info> '.$count.' playlist item(s)');
        }
    }
    
    protected function newQuery()
    {
        return DB::table('playlists_bubbles_pivot')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('bubbles')
                      ->whereRaw('bubbles.id = playlists_bubbles_pivot.bubble_id');
            });
    }
}
