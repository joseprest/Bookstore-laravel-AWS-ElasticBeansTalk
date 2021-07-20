<?php

namespace Manivelle\Events;

use Illuminate\Queue\SerializesModels;

use Panneau\Bubbles\Support\BubbleType;

class BubbleTypeRegistered extends Event
{
    use SerializesModels;
    
    public $bubbleType;
    public $key;
    
    public function __construct(BubbleType $bubbleType, $key = null)
    {
        $this->bubbleType = $bubbleType;
        $this->key = $key;
    }
}
