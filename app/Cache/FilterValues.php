<?php

namespace Manivelle\Cache;

use Manivelle\Models\Screen;
use Closure;
use Manivelle;

class FilterValues extends Cache
{
    protected $key = 'filters_values';
    protected $cacheOnCloud = true;
    protected $filterValuesKey = 'values';
    
    public function getData()
    {
        $filter = $this->getFilter();
        $values = isset($filter[$this->filterValuesKey]) ? $filter[$this->filterValuesKey]():[];
        return $values;
    }
    
    public function getFilter()
    {
        return $this->getFilterFromItem($this->item);
    }
    
    public function getFilterFromItem($item)
    {
        $filtersMethod = 'get'.studly_case(array_get($item, 'type', 'filters'));
        $channelType = Manivelle::channelType($item['channel_type']);
        $filters = $channelType->{$filtersMethod}();
        return array_first($filters, function ($index, $filter) use ($item) {
            return $item['name'] === $filter['name'];
        });
    }
    
    public function getKey()
    {
        $filter = $this->getFilter();
        $key = [];
        $key[] = config('manivelle.core.cache_namespace', '');
        $key[] = preg_replace('/[^a-z]+/i', '', $this->item['channel_type']);
        $key[] = preg_replace('/'.preg_quote(\Manivelle\Support\ChannelType::class).'\\\/', '', $this->name);
        $key[] = array_get($filter, 'name');
        return implode('_', $key);
    }
}
