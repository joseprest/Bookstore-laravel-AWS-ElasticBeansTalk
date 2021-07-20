<?php

namespace Manivelle\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Database\Eloquent\Model;

use Manivelle\Models\Bubble;
use Manivelle\Contracts\Bubbles\Cleanable;
use Manivelle\Jobs\CleanBubble;
use Manivelle\Jobs\CreateCache;

use Log;
use Artisan;

class CleanBubbles extends Command
{
    use DispatchesJobs;
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manivelle:clean:bubbles {type?} {--start=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean bubbles';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //Disable model events
        $eventDisptacher = Model::getEventDispatcher();
        Model::unsetEventDispatcher();
        
        $currentId = $this->option('start') ? ($this->option('start')-1):0;
        $bubbleTypes = [];
        $cleanedBubbles = [];
        while ($item = $this->getNextBubble($currentId)) {
            $currentId = $item->id;
            
            //Get bubble type
            if (empty($item->type)) {
                continue;
            }
            if (!isset($bubbleTypes[$item->type])) {
                try {
                    $bubbleTypes[$item->type] = app('panneau')->bubbleType($item->type);
                } catch (\Exception $e) {
                    Log::error($e);
                    continue;
                }
            }
            $bubbleType = $bubbleTypes[$item->type];
            
            //Check if cleanable
            if ($bubbleType instanceof Cleanable && $bubbleType->shouldCleanBubble($item)) {
                try {
                    $this->line('<comment>Cleaning:</comment> Bubble #'.$item->id.' of type '.$item->type);
                    $this->dispatch(new CleanBubble($item));
                    $cleanedBubbles[] = $currentId;
                } catch (\Exception $e) {
                    Log::error($e);
                }
            } else {
                $this->line('<comment>Skipped:</comment> Bubble #'.$item->id.' of type '.$item->type);
            }
        }
        
        if ($currentId === 0) {
            $this->line('No bubbles to clean.');
        }
        
        $cleanedBubbles = [1];
        
        if (sizeof($cleanedBubbles)) {
            Artisan::call('manivelle:caches:create', [
                '--type' => ['screens'],
                '--clear' => true
            ]);
            
            Artisan::call('manivelle:caches:clear', [
                '--type' => ['suggestions'],
                '--id' => $cleanedBubbles
            ]);
            
            $pages = $this->getPagesFromIds($cleanedBubbles);
            Artisan::call('manivelle:caches:create', [
                '--type' => ['bubbles'],
                '--id' => $pages,
                '--clear' => true
            ]);
        }
        
        //Enable event listener
        Model::setEventDispatcher($eventDisptacher);
    }
    
    protected function getNextBubble($currentId)
    {
        $type = $this->argument('type');
        
        $query = Bubble::where('id', '>', $currentId)
                ->orderBy('id', 'asc');
        
        if (!empty($type)) {
            $query->where('type', $type);
        } else {
            $query->where('type', '!=', 'filter');
        }
        
        return $query->first();
    }
    
    protected function getPagesFromIds($ids)
    {
        $pages = [];
        $countPerPage = config('manivelle.screens.bubbles_per_page', 300);
        foreach ($ids as $id) {
            $page = ceil($id/$countPerPage);
            if (!in_array($page, $pages)) {
                $pages[] = $page;
            }
        }
        
        return $pages;
    }
}
