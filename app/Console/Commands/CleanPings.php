<?php

namespace Manivelle\Console\Commands;

use Illuminate\Console\Command;

use Manivelle\Models\ScreenPing;
use Carbon\Carbon;

class CleanPings extends Command
{
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manivelle:clean:pings {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean screen pings';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $force = $this->option('force');
        
        $this->line('<comment>Cleaning:</comment> Screen pings...');
        $query = ScreenPing::where('created_at', '<', Carbon::now()->subDays(3));
        $cleanCount = $query->count();
        
        if ($force || $this->confirm('Are you sur you want to clean '.$cleanCount.' ping(s)? [y|N]')) {
            $query->delete();
            $count = ScreenPing::count();
            $this->line('<info>Cleaned:</info> '.$cleanCount.' cleaned '.$count.' remaining screen pings.');
        }
    }
}
