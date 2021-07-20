<?php

namespace Manivelle\Events;

use Illuminate\Queue\SerializesModels;

class ScreenCacheCreated extends Event
{
    use SerializesModels;
    
    public $screen;
    public $cache;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($screen, $cache)
    {
        $this->screen = $screen;
        $this->cache = $cache;
    }
}
