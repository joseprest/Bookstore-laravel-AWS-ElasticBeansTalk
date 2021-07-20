<?php

namespace Manivelle\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

use Manivelle;
use Manivelle\Models\Screen;
use Manivelle\Models\Bubble;
use Manivelle\Jobs\CreateCache;
use DB;

class CachesCreate extends Command
{
    use DispatchesJobs;
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manivelle:caches:create {--type=*} {--id=*} {--start=} {--clear} {--now}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create cache';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $types = $this->option('type');
        if (!sizeof($types)) {
            $types = [
                'screens',
                'channels',
                'bubbles',
                /*'suggestions',*/
                'bubbles_filters',
                'filters'
            ];
        }
        
        $this->info('Creating caches...');
        $this->output->writeLn('---');
        
        if (in_array('screens', $types)) {
            $this->createScreensCaches();
            $this->output->writeLn('---');
        }
        
        if (in_array('bubbles_filters', $types)) {
            $this->createBubblesFiltersCaches();
            $this->output->writeLn('---');
        }
        
        if (in_array('filters', $types)) {
            $this->createFiltersCaches();
            $this->output->writeLn('---');
        }
        
        if (in_array('channels', $types)) {
            $this->createChannelsCaches();
            $this->output->writeLn('---');
        }
        
        if (in_array('bubbles', $types)) {
            $this->createBubblesCaches();
            $this->output->writeLn('---');
        }
        
        if (in_array('suggestions', $types)) {
            $this->createBubblesSuggestionsCaches();
            $this->output->writeLn('---');
        }
        
        $this->info('All caches created.');
    }
    
    protected function createScreensCaches()
    {
        $this->comment('Creating cache for screens...');
        
        $clear = $this->option('clear');
        $now = $this->option('now');
        $ids = $this->option('id');
        $specificIds = sizeof($ids) ? true:false;
        $start = $this->option('start') ? $this->option('start'):1;
        
        $caches = Manivelle::caches(\Manivelle\Models\Screen::class);
        $id = $specificIds ? array_shift($ids):$start;
        while ($item = \Manivelle\Models\Screen::where('id', $specificIds ? '=':'>=', $id)
                ->orderBy('id', 'asc')
                ->first()
        ) {
            if (!$item->linked) {
                $this->output->writeLn('<comment>Skip cache:</comment> Screen #'.$item->id.' not linked.');
                if ($specificIds && !sizeof($ids)) {
                    break;
                }
                $id = $specificIds ? array_shift($ids):($item->id+1);
                continue;
            }
            
            foreach ($caches as $cache) {
                $job = new CreateCache($cache, $item, $clear);
                $message = 'Cache '.$cache.' <comment>for</comment> screen #'.$item->id.'.';
                $this->dispatchCacheJob($job, $message);
                
                $data = null;
                $itemCache = null;
            }
            
            if ($specificIds && !sizeof($ids)) {
                break;
            }
            $id = $specificIds ? array_shift($ids):($item->id+1);
        }
        
        $this->info('Caches for screens created.');
    }
    
    protected function createChannelsCaches()
    {
        $this->comment('Creating cache for channels...');
        
        $clear = $this->option('clear');
        $now = $this->option('now');
        $ids = $this->option('id');
        $specificIds = sizeof($ids) ? true:false;
        $start = $this->option('start') ? $this->option('start'):1;
        
        $caches = Manivelle::caches(\Manivelle\Models\Channel::class);
        $id = $specificIds ? array_shift($ids):$start;
        while ($item = \Manivelle\Models\Channel::where('id', $specificIds ? '=':'>=', $id)
                ->orderBy('id', 'asc')
                ->first()
        ) {
            foreach ($caches as $cache) {
                $job = new CreateCache($cache, $item, $clear);
                
                $message = 'Cache '.$cache.' <comment>for</comment> channel #'.$item->id.'.';
                $this->dispatchCacheJob($job, $message);
            }
            
            if ($specificIds && !sizeof($ids)) {
                break;
            }
            $id = $specificIds ? array_shift($ids):($item->id+1);
        }
        
        $this->info('Caches for channels created.');
    }
    
    protected function createBubblesCaches()
    {
        $this->comment('Creating cache for bubbles...');
        
        $clear = $this->option('clear');
        $now = $this->option('now');
        $ids = $this->option('id');
        $start = $this->option('start') ? $this->option('start'):1;
        
        //Bubbles page cache
        $cache = \Manivelle\Models\Bubble::class.'\\page_json';
        $lastBubble = DB::table('bubbles')
                            ->select('id')
                            ->orderBy('id', 'desc')
                            ->first();
        
        $countPerPage = config('manivelle.screens.bubbles_per_page');
        $totalPage = $lastBubble ? ceil($lastBubble->id / $countPerPage):0;
        $pages = sizeof($ids) ? $ids:range($start, $totalPage);
        
        foreach ($pages as $page) {
            $job = new CreateCache($cache, [
                'page' => $page,
                'count' => $countPerPage
            ], $clear);
            
            $message = 'Cache '.$cache.' <comment>for</comment> page #'.$page.'.';
            $this->dispatchCacheJob($job, $message);
        }
        
        $this->info('Caches for bubbles created.');
    }
    
    protected function createBubblesSuggestionsCaches()
    {
        $this->comment('Creating cache for bubbles suggestions...');
        
        $clear = $this->option('clear');
        $now = $this->option('now');
        $ids = $this->option('id');
        
        $currentId = $this->option('start') ? $this->option('start'):0;
        $cache = \Manivelle\Models\Bubble::class.'\\suggestions';
        while ($bubble = $this->getNextBubble($currentId)) {
            $currentId = $bubble->id;
            
            $job = new CreateCache($cache, $bubble, $clear);
            
            $message = 'Cache '.$cache.' <comment>for</comment> bubble #'.$currentId.'.';
            $this->dispatchCacheJob($job, $message);
        }
        
        $this->info('Caches for bubbles suggestions created.');
    }
    
    protected function createBubblesFiltersCaches()
    {
        $this->comment('Creating cache for bubbles filters...');
        
        $clear = $this->option('clear');
        $now = $this->option('now');
        $ids = $this->option('id');
        $keys = ['values', 'tokens'];
        
        $types = Manivelle::channelTypes();
        $filtersKeys = [];
        foreach ($types as $typeName) {
            $type = Manivelle::channelType($typeName);
            $bubblesFilters = $type->getBubblesFilters();
            foreach ($bubblesFilters as $filter) {
                foreach ($keys as $key) {
                    if (!isset($filter[$key])) {
                        continue;
                    }
                    $cache = \Manivelle\Support\ChannelType::class.'\\bubble_filter_'.$key;
                    $data = [
                        'channel_type' => $typeName,
                        'type' => 'bubbles_filters',
                        'name' => $filter['name']
                    ];
                    $job = new CreateCache($cache, $data, $clear);
                    
                    $message = 'Cache '.$cache.' <comment>for</comment> bubble filter '.$key.' of '.$filter['name'].'.';
                    $this->dispatchCacheJob($job, $message);
                }
            }
        }
        
        $this->info('Caches for bubbles filters created.');
    }
    
    protected function createFiltersCaches()
    {
        $this->comment('Creating cache for filters...');
        
        $clear = $this->option('clear');
        $now = $this->option('now');
        $ids = $this->option('id');
        $keys = ['values', 'tokens'];
        
        $types = Manivelle::channelTypes();
        $filtersKeys = [];
        foreach ($types as $typeName) {
            $type = Manivelle::channelType($typeName);
            $bubblesFilters = $type->getFilters();
            foreach ($bubblesFilters as $filter) {
                foreach ($keys as $key) {
                    if (!isset($filter[$key])) {
                        continue;
                    }
                    $cache = \Manivelle\Support\ChannelType::class.'\\filter_'.$key;
                    $data = [
                        'channel_type' => $typeName,
                        'name' => $filter['name']
                    ];
                    $job = new CreateCache($cache, $data, $clear);
                    
                    $message = 'Cache '.$cache.' <comment>for</comment> filter '.$key.' of '.$filter['name'].'.';
                    $this->dispatchCacheJob($job, $message);
                }
            }
        }
        
        $this->info('Caches for filters created.');
    }
    
    protected function dispatchCacheJob($job, $message)
    {
        $now = $this->option('now');
        if ($now) {
            $this->dispatchNow($job);
            $this->output->writeLn('<info>Created:</info> '.$message);
        } else {
            $this->output->writeLn('<comment>Dispatching:</comment> '.$message);
            $this->dispatch($job);
        }
    }
    
    protected function getNextBubble($currentId)
    {
        $query = Bubble::where('id', '>', $currentId)
                ->orderBy('id', 'asc')
                ->where('type', '!=', 'filter');
        
        return $query->first();
    }
}
