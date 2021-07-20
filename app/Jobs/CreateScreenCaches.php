<?php

namespace Manivelle\Jobs;

use Manivelle\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Manivelle;
use Manivelle\Models\Screen;

class CreateScreenCaches extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    
    public $connection = 'priority';

    public $screen;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Screen $screen)
    {
        $this->screen = $screen;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $caches = Manivelle::caches(\Manivelle\Models\Screen::class);
        foreach ($caches as $cache) {
            $this->dispatch(new CreateCache($cache, $this->screen, true, 'priority'));
        }
    }
}
