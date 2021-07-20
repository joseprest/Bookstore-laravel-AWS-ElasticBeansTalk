<?php

namespace Manivelle\Cache;

use Manivelle\Models\Screen;
use Closure;
use Manivelle;

class ChannelFilters extends Cache
{
    protected $key = 'channel_filters_with_values';
    protected $cacheOnCloud = true;
    
    public function getData()
    {
        $channelType = $this->item->getChannelType();
        $filters = $channelType->getFilters();
        $items = [];
        $valuesFields = ['values', 'tokens'];
        foreach ($filters as $filter) {
            foreach ($valuesFields as $key) {
                if (isset($filter[$key]) && $filter[$key] instanceof Closure) {
                    $valuesMethod = 'getFilter'.studly_case($key);
                    $filter[$key] =  $channelType->{$valuesMethod}($filter['name']);
                }
            }
            
            foreach ($filter as $key => $value) {
                if ($value instanceof Closure) {
                    unset($filter[$key]);
                }
            }
            
            $items[] = $filter;
        }
        
        return $items;
    }
    
    public function forget()
    {
        parent::forget();
        
        $channelType = $this->item->getChannelType();
        $filters = $channelType->getFilters();
        $valuesFields = ['values', 'tokens'];
        foreach ($filters as $filter) {
            foreach ($valuesFields as $key) {
                $channelType->getFilterValuesCache($filter['name'], $key, 'filters')
                    ->forget();
            }
        }
    }
}
