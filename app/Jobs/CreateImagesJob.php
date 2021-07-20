<?php

namespace Manivelle\Jobs;

use Log;
use Config;
use Manivelle\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Log\Writer;
use Symfony\Component\Console\Output\ConsoleOutput;

use Folklore\EloquentMediatheque\Models\Picture;

class CreateImagesJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    
    protected $picture;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Picture $picture)
    {
        $this->picture = $picture;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Writer $log, ConsoleOutput $output)
    {
        Config::set('image.host', '');
        $image = app('image');
        
        $filters = ['background_blur', 'thumbnail', 'thumbnail_snippet'];
        foreach ($filters as $filter) {
            try {
                $start = microtime(true);
                $path = $image->url($this->picture->filename, [$filter]);
                $response = $image->proxy($path);
                $end = microtime(true);
                $elapsed = round($end - $start, 2).' second(s)';
                
                $message = 'Image "'.$filter.'" for picture #'.$this->picture->id.' in '.$elapsed;
                if (app()->runningInConsole()) {
                    $output->writeLn('<info>Created:</info> '.$message);
                } else {
                    $log->info('Created: '.$message);
                }
            } catch (\Exception $e) {
                $log->error($e);
            }
        }
    }
}
