<?php

namespace Manivelle\Jobs;

use Manivelle;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Log\Writer;
use Symfony\Component\Console\Output\ConsoleOutput;

use Event;
use Manivelle\Events\CacheCleared;

class ClearCache extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    public $connection;
    public $cache;
    public $model;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($cache, $model, $connection = null)
    {
        $this->cache = $cache;
        $this->model = $model;
        $this->connection = $connection;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Writer $log, ConsoleOutput $output)
    {
        $itemCache = Manivelle::cache($this->cache)
                                ->setItem($this->model);
        
        $id = $this->model instanceof Model ? $this->model->id:json_encode($this->model);
        
        $itemCache->forget();
        
        $message = $this->cache.' with key '.$id;
        if (app()->runningInConsole()) {
            $output->writeLn('<info>Cleared:</info> '.$message);
        } else {
            $log->info('Cleared: '.$message);
        }
        
        Event::fire(new CacheCleared($this->cache, $this->model));
    }
}
