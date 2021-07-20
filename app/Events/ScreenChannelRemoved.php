<?php

namespace Manivelle\Events;

use Illuminate\Queue\SerializesModels;
use Manivelle\Models\Screen;
use Manivelle\Models\ScreenChannel;

class ScreenChannelRemoved extends Event
{
    use SerializesModels;
    
    public $screen;
    public $channel;
    
    public function __construct(Screen $screen, ScreenChannel $channel)
    {
        $this->screen = $screen;
        $this->channel = $channel;
    }
}
