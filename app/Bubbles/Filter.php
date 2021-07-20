<?php namespace Manivelle\Bubbles;

use Closure;
use Manivelle\Support\BubbleType;
use Cache;
use Manivelle\Models\Channel;
use Manivelle;
use App;

class Filter extends BubbleType
{
    
    protected static $bubblesFilters;
    protected static $bubblesFiltersValues = [];
    
    protected $attributes = [
        'type' => 'filter'
    ];
    
    public function fields()
    {
        return [
            [
                'name' => 'filters',
                'type' => 'filters'
            ]
        ];
    }
    
    public function snippet()
    {
        return [
            'title' => function () {
                return 'Sélection automatique';
            },
            'description' => function ($fields, $item) {
                
                $locale = App::getLocale();
                $cacheKey = 'bubble_filter_description_'.$item->id.'_'.$locale;
                
                $description = Cache::rememberForever($cacheKey, function () use ($fields) {
                    $description = [];
                    if ($fields->filters) {
                        $bubblesFilters = static::getBubblesFilters();
                        foreach ($fields->filters as $filter) {
                            $bubbleFilter = array_get($bubblesFilters, $filter['name'].'.filter');
                            $values = $bubbleFilter ? self::getFilterValues($filter['name']):null;
                            if (!$values) {
                                continue;
                            }
                            
                            $filterValue = isset($filter['value']) ? (array)$filter['value']:null;
                            $value = array_first($values, function ($key, $value) use ($filterValue) {
                                return in_array($value['value'], $filterValue);
                            });
                            if ($value && isset($value['label'])) {
                                $description[] = $bubbleFilter['label'].': '.$value['label'];
                            }
                        }
                    }
                    
                    $description = implode("\n", $description);
                    
                    return $description;
                });
                
                return $description;
            }
        ];
    }
    
    public static function getFilterValues($name)
    {
        if (isset(self::$bubblesFiltersValues[$name])) {
            return self::$bubblesFiltersValues[$name];
        }
        
        $filters = self::getBubblesFilters();
        $filter = isset($filters[$name]) ? $filters[$name]:null;
        if (!$filter) {
            return null;
        }
        
        $channelType = Manivelle::channelType($filter['channelType']);
        $name = array_get($filter, 'filter.name');
        self::$bubblesFiltersValues[$name] = $channelType->getBubbleFilterValues($name);
        
        /*Manivelle::
        
        $channel = Channel::where('type', $filter['channelType'])->first();
        foreach ($channel->bubbles_filters as $filter) {
            self::$bubblesFiltersValues[$filter['name']] = array_get($filter, 'values', array_get($filter, 'tokens', []));
        }*/
        
        return self::$bubblesFiltersValues[$name];
    }
    
    public static function getBubblesFilters()
    {
        if (self::$bubblesFilters) {
            return self::$bubblesFilters;
        }
        
        $manivelle = app('manivelle');
        $channelTypes = $manivelle->channelTypes();
        $filters = [];
        foreach ($channelTypes as $channelTypeKey) {
            $channelType = $manivelle->channelType($channelTypeKey);
            $bubblesFilters = $channelType->getBubblesFilters();
            foreach ($bubblesFilters as $filter) {
                $filters[$filter['name']] = [
                    'channelType' => $channelTypeKey,
                    'filter' => $filter
                ];
            }
        }
        
        self::$bubblesFilters = $filters;
        
        return $filters;
    }
}
