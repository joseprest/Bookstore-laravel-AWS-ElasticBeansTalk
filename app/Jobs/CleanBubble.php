<?php

namespace Manivelle\Jobs;

use Illuminate\Queue\SerializesModels;
use Symfony\Component\Console\Output\ConsoleOutput;

use Manivelle;
use DB;
use Log;
use Manivelle\Models\Bubble;

class CleanBubble extends Job
{
    use SerializesModels;

    public $bubble;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($bubble)
    {
        $this->bubble = $bubble;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ConsoleOutput $output)
    {
        $bubble = $this->bubble;
        
        //Deleting metadatas
        $count = $this->cleanRelation($bubble, 'metadatas');
        if ($count) {
            $output->writeLn('<info>Deleted:</info> '.$count.' metadata(s).');
        }
        
        //Deleting pictures
        $count = $this->cleanRelation($bubble, 'pictures');
        if ($count) {
            $output->writeLn('<info>Deleted:</info> '.$count.' picture(s).');
        }
        
        //Deleting texts
        $count = $this->cleanRelation($bubble, 'texts');
        if ($count) {
            $output->writeLn('<info>Deleted:</info> '.$count.' text(s).');
        }
        
        //Deleting channel bubbles
        $count = $this->cleanPivot($bubble, 'channels_bubbles_pivot');
        if ($count) {
            $output->writeLn('<info>Deleted:</info> '.$count.' channels_bubbles_pivot item(s).');
        }
            
        //Deleting playlist items
        $count = $this->cleanPivot($bubble, 'playlists_bubbles_pivot');
        if ($count) {
            $output->writeLn('<info>Deleted:</info> '.$count.' playlists_bubbles_pivot item(s).');
        }
        
        //Delete bubble
        $bubble->delete();
        
        $output->writeLn('<info>Cleaned:</info> Bubble #'.$bubble->id);
    }
    
    protected function cleanRelation($bubble, $relation)
    {
        $count = 0;
        foreach ($bubble->{$relation} as $item) {
            try {
                $item->delete();
                $count++;
            } catch (\Exception $e) {
                Log::error($e);
            }
        }
        
        $table = $bubble->metadatas()->getTable();
        $idColumn = $bubble->metadatas()->getForeignKey();
        $typeColumn = $table.'.'.$bubble->metadatas()->getMorphType();
        
        DB::table($table)
            ->where($idColumn, $bubble->id)
            ->where($typeColumn, Bubble::class)
            ->delete();
        
        return $count;
    }
    
    protected function cleanPivot($bubble, $table, $column = 'bubble_id')
    {
        return DB::table($table)
            ->where($column, $bubble->id)
            ->delete();
    }
}
