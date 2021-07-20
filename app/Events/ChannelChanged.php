<?php

namespace Manivelle\Events;

use Manivelle\Models\Channel;
use Illuminate\Queue\SerializesModels;

class ChannelChanged extends Event
{
    use SerializesModels;
    
    public $channel;
    
    public function __construct(Channel $channel)
    {
        $this->channel = $channel;
    }
}
