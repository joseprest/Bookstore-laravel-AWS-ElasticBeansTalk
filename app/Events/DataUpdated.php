<?php

namespace Manivelle\Events;

use Manivelle\Models\Screen;
use Illuminate\Queue\SerializesModels;

class DataUpdated extends Event
{
    use SerializesModels;
    
    public $screen;
    
    public function __construct(Screen $screen)
    {
        $this->screen = $screen;
    }
}
