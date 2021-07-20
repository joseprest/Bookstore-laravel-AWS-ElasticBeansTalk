<?php

namespace Manivelle\Console\Commands;

use Manivelle;
use Manivelle\Jobs\CreateImagesJob;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Manivelle\Models\Bubble;

class ImagesCreate extends Command
{
    use DispatchesJobs;
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create images';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->output->writeLn('Creating images...');
        
        $page = 1;
        $totalPage = -1;
        do {
            $bubbles = Bubble::paginate(100, ['*'], 'page', $page);
            if ($totalPage === -1) {
                $totalPage = $bubbles->lastPage();
                $this->output->writeLn('Total page: '.$totalPage);
            }
            
            $this->output->writeLn('<comment>Adding:</comment> Page #'.$page.' with '.sizeof($bubbles).' bubbles to queue...');
            
            foreach ($bubbles as $bubble) {
                foreach ($bubble->pictures as $picture) {
                    $this->output->writeLn('<comment>Adding:</comment> Picture #'.$picture->id.' at '.$picture->link.'...');
                    $this->dispatch(new CreateImagesJob($picture));
                }
            }
            $page++;
        } while ($page < $totalPage);
    }
}
