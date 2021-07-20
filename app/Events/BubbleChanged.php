<?php

namespace Manivelle\Events;

use Manivelle\Models\Bubble;
use Illuminate\Queue\SerializesModels;

class BubbleChanged extends Event
{
    use SerializesModels;
    
    public $bubble;
    
    public function __construct(Bubble $bubble)
    {
        $this->bubble = $bubble;
    }
}
