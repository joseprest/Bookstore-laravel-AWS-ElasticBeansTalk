<?php

namespace Manivelle\Console\Commands;

use Manivelle;

use Illuminate\Console\Command;

use Illuminate\Foundation\Bus\DispatchesJobs;

use Manivelle\Models\Screen;
use Manivelle\Models\Bubble;
use Manivelle\Models\Playlist;
use Manivelle\Models\Organisation;

class CachesClear extends Command
{
    use DispatchesJobs;
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manivelle:caches:clear {--type=*} {--id=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear caches';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $types = $this->option('type');
        if (!sizeof($types)) {
            $types = ['screens', 'channels', 'bubbles', 'suggestions'];
        }
        
        //Screens cache
        if (in_array('screens', $types)) {
            $this->clearScreenCache();
        }
        
        //Bubbles page cache
        if (in_array('bubbles', $types)) {
            $this->clearBubblesPageCache();
        }
        
        //Bubbles page cache
        if (in_array('suggestions', $types)) {
            $this->clearBubbleSuggestionsCache();
        }
        
        $this->output->writeLn('---');
        $this->info('All caches cleared.');
    }
    
    protected function clearScreenCache()
    {
        $this->output->writeLn('Clearing cache for screens...');
        $caches = Manivelle::caches(\Manivelle\Models\Screen::class);
        $id = 1;
        while ($item = \Manivelle\Models\Screen::where('id', '>=', $id)
                ->orderBy('id', 'asc')
                ->first()
        ) {
            foreach ($caches as $cache) {
                try {
                    $itemCache = Manivelle::cache($cache)->setItem($item);
                    $itemCache->forget();
                    $this->output->writeLn(
                        '<info>Cleared:</info> Cache '.$cache.
                        ' <comment>for</comment> screen #'.$item->id.'.'
                    );
                } catch (\Exception $e) {
                    $this->output->writeLn('<error>Error with cache '.$cache.':</error> '.$e->getMessage());
                }
            }
            $id = $item->id+1;
        }
    }
    
    protected function clearBubblesPageCache()
    {
        $cache = \Manivelle\Models\Bubble::class.'\\page_json';
        $lastBubble = Bubble::orderBy('id', 'desc')->first();
        $countPerPage = config('manivelle.screens.bubbles_per_page');
        $totalPage = ceil($lastBubble->id / $countPerPage);
        for ($i = 1; $i <= $totalPage; $i++) {
            try {
                $itemCache = Manivelle::cache($cache)->setItem([
                    'page' => $i,
                    'count' => $countPerPage
                ]);
                $itemCache->forget();
                $this->output->writeLn(
                    '<info>Cleared:</info> Cache '.$cache.
                    ' <comment>for</comment> page #'.$i.'.'
                );
            } catch (\Exception $e) {
                $this->output->writeLn('<error>Error with cache '.$cache.':</error> '.$e->getMessage());
            }
        }
    }
    
    protected function clearBubbleSuggestionsCache()
    {
        $cache = \Manivelle\Models\Bubble::class.'\\suggestions';
        
        $ids = $this->option('id');
        $exact = $ids && sizeof($ids) ? true:false;
        
        $currentId = $exact ? array_shift($ids):0;
        while ($bubble = $this->getNextBubble($currentId, $exact)) {
            try {
                $itemCache = Manivelle::cache($cache)->setItem($bubble);
                $itemCache->forget();
                $this->output->writeLn(
                    '<info>Cleared:</info> Cache '.$cache.
                    ' <comment>for</comment> bubble #'.$currentId.'.'
                );
            } catch (\Exception $e) {
                $this->output->writeLn('<error>Error with cache '.$cache.':</error> '.$e->getMessage());
            }
            
            if ($exact && !sizeof($ids)) {
                break;
            }
            $currentId = $exact ? array_shift($ids):$bubble->id;
        }
    }
    
    protected function getNextBubble($currentId, $exact = false)
    {
        $query = Bubble::where('id', $exact ? '=':'>', $currentId)
                ->orderBy('id', 'asc')
                ->where('type', '!=', 'filter');
        
        return $query->first();
    }
}
