<?php

namespace Manivelle\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;

use Illuminate\Foundation\Bus\DispatchesJobs;

use Manivelle\Models\Source;
use Manivelle\Models\SourceSync;

use Carbon\Carbon;

use Manivelle\Jobs\SourceStartSync;

class SourcesStatus extends Command
{
    use DispatchesJobs;
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manivelle:sources:status {handle?} {--type=} {--f|follow}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sources status';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $manivelle = app('manivelle');
        
        $table = new Table($this->output);
        
        
        $query = Source::query();
        
        if ($this->argument('handle')) {
            $query->where('handle', $this->argument('handle'));
        }
        
        if ($this->option('type')) {
            $query->where('type', $this->option('type'));
        }
        
        $follow = $this->option('follow');
        
        $overwrite = false;
        while (true) {
            $sources = $query->get();
            $rows = [];
            foreach ($sources as $source) {
                $startedSync = $source->getCurrentSync();
                $hasSync = $source->syncs()->count() > 0 ? true:false;
                $isSyncing = $source->isSyncing();
                $isFinished = $startedSync ? $startedSync->isFinished():false;
                $jobsWaitingCount = $source->jobs()->count();
                $jobsRunningCount = $source->jobs()->where('reserved', 1)->count();
                if ($isSyncing && !$isFinished) {
                    $status = '<info>running</info>';
                } elseif ($hasSync) {
                    if (!$startedSync || $isFinished) {
                        $status = $jobsWaitingCount ? '<comment>finishing</comment>':'finished';
                    } else {
                        $status = $jobsWaitingCount ? '<comment>stopping</comment>':'stopped';
                    }
                } else {
                    $status = '-';
                }
                $time = $isSyncing ? $startedSync->updated_at->diffForHumans($startedSync->created_at, true):'-';
                $rows[] = [
                    $source->name,
                    $startedSync ? $startedSync->id:'-',
                    $status,
                    $startedSync ? sizeof(array_get($startedSync->state, 'jobs_synced', [])):0,
                    $jobsWaitingCount,
                    $jobsRunningCount,
                    $time
                ];
            }
            
            if ($overwrite) {
                $rowsCount = sizeof($sources) + 4/* + 2*/;
                $this->output->write("\x0D");
                $this->output->write("\x1B[2K");
                $this->output->write(str_repeat("\x1B[1A\x1B[2K", $rowsCount));
            } elseif ($follow) {
                $overwrite = true;
            }
            
            $table->setHeaders([
                array('Source', 'Sync ID', 'Status', 'Done', 'Waiting', 'Running', 'Time')
            ]);
            $table->setRows($rows);
            $table->render();
            
            if (!$follow) {
                break;
            }
            sleep(3);
        }
    }
}
