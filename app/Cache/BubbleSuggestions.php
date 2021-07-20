<?php

namespace Manivelle\Cache;

use Manivelle\Models\Screen;
use Closure;

class BubbleSuggestions extends Cache
{
    protected $key = 'bubble_suggestions';
    protected $cacheOnCloud = true;
    
    public function getData()
    {
        $bubbleType = $this->item->bubbleType();
        
        if (!$bubbleType || !method_exists($bubbleType, 'getSuggestionsForBubble')) {
            return [];
        }
        
        $items = $bubbleType->getSuggestionsForBubble($this->item);
        
        return $items;
    }
}
