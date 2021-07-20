<?php

namespace Manivelle\Contracts\Bubbles;

use Manivelle\Models\Bubble;

interface Cleanable
{
    
    public function shouldCleanBubble(Bubble $bubble);
}
