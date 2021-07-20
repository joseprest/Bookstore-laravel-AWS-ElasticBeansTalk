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

class CleanMediatheque extends Command
{
    use DispatchesJobs;
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manivelle:clean:mediatheque {type?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean mediatheque';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        
        $types = [
            'metadata' => 'metadatable',
            'text' => 'writable',
            'picture' => 'picturable'
        ];
        
        foreach ($types as $key => $morphName) {
            $table = 'mediatheque_'.str_plural($key);
            $currentId = -1;
            while ($item = $this->getNextItem($currentId, $key, $table, $morphName)) {
                DB::table($table)->where('id', $item->id)->delete();
                $this->line('<info>Deleted:</info> Item '.$key.' #'.$item->id);
                $currentId = $item->id;
            }
        }
    }
    
    public function getNextItem($id, $key, $table, $morphName)
    {
        $morphTable = 'mediatheque_'.str_plural($morphName);
        
        return DB::table($table)
                    ->select($table.'.id as id')
                    ->leftJoin($morphTable, $table.'.id', '=', $morphTable.'.'.$key.'_id')
                    ->leftJoin('bubbles', $morphTable.'.'.$morphName.'_id', '=', 'bubbles.id')
                    ->leftJoin('channels', $morphTable.'.'.$morphName.'_id', '=', 'channels.id')
                    ->leftJoin('organisations', $morphTable.'.'.$morphName.'_id', '=', 'organisations.id')
                    ->where(function ($query) use ($morphTable, $morphName) {
                        $query->whereNull($morphTable.'.id');
                        $query->orWhere(function ($query) use ($morphName) {
                            $query->where($morphName.'_type', '=', 'Manivelle\\Models\\Bubble');
                            $query->whereNull('bubbles.id');
                        });
                        $query->orWhere(function ($query) use ($morphName) {
                            $query->where($morphName.'_type', '=', 'Manivelle\\Models\\Channel');
                            $query->whereNull('channels.id');
                        });
                        $query->orWhere(function ($query) use ($morphName) {
                            $query->where($morphName.'_type', '=', 'Manivelle\\Models\\Organisation');
                            $query->whereNull('organisations.id');
                        });
                    })
                    ->where($table.'.id', '>', $id)
                    ->orderBy($table.'.id', 'asc')
                    ->first();
    }
}
