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
use Manivelle\Events\CacheCreated;

class CreateCache extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    public $connection;
    public $cache;
    public $model;
    public $clear;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($cache, $model, $clear = false, $connection = null)
    {
        $this->cache = $cache;
        $this->model = $model;
        $this->clear = $clear;
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
        
        $id = $itemCache->getKey();
        if ($this->clear) {
            $this->dispatchNow(new ClearCache($this->cache, $this->model));
        }
        
        $start = microtime(true);
        $itemCache->put();
        $end = microtime(true);
        $elapsed = round($end - $start, 2).' second(s)';
        
        $message = '"'.$this->cache.'" with key "'.$id.'"';
        if (app()->runningInConsole()) {
            $output->writeLn('<info>Cached:</info> '.$message.' in '.$elapsed);
        } else {
            $log->info('Cached: '.$message.' in '.$elapsed);
        }
        
        unset($itemCache);
        
        Event::fire(new CacheCreated($this->cache, $this->model));
    }
}
