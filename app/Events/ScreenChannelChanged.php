<?php

namespace Manivelle\Events;

use Illuminate\Queue\SerializesModels;
use Manivelle\Models\ScreenChannel;

class ScreenChannelChanged extends Event
{
    use SerializesModels;
    
    public $channel;
    
    public function __construct(ScreenChannel $channel)
    {
        $this->channel = $channel;
    }
}
