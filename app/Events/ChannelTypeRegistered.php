<?php

namespace Manivelle\Events;

use Illuminate\Queue\SerializesModels;

use Manivelle\Support\ChannelType;

class ChannelTypeRegistered extends Event
{
    use SerializesModels;
    
    public $channelType;
    public $key;
    
    public function __construct(ChannelType $channelType, $key = null)
    {
        $this->channelType = $channelType;
        $this->key = $key;
    }
}
