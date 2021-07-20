<?php namespace Manivelle;

use Illuminate\Support\Collection;
use Illuminate\Support\Fluent;
use Carbon\Carbon;
use Manivelle as ManivelleFacade;

class Timeline extends Fluent
{
    protected $attributes = [
        'cycles' => [],
        'bubbleIds' => []
    ];
    
    public static function makeFromItems($items, $start = null, $end = null)
    {
        $items = self::getTimelineItemsFromItems($items);
        
        return self::makeFromTimelineItems($items, $start, $end);
    }
    
    public static function makeFromBubbles($bubbles, $start = null, $end = null)
    {
        $items = self::getItemsFromBubbles($bubbles);
        
        return self::makeFromTimelineItems($items, $start, $end);
    }
    
    protected static function makeFromTimelineItems($items, $start = null, $end = null)
    {
        if (!sizeof($items)) {
            return new Fluent([
                'bubbles' => [],
                'bubbleIds' => [],
                'cycles' => []
            ]);
        }
        
        if (!$start) {
            $start = Carbon::now();
        } else {
            $start = Carbon::parse($start);
        }
        
        if (!$end) {
            $end = $start->copy()->addHour();
        } else {
            $end = Carbon::parse($end);
        }
        
        $startTime = $start->timestamp;
        $endTime = $end->timestamp;
        
        $dayStartDate = $start->copy()->setTime(0, 0, 0);
        $dayEndDate = $end->copy()->setTime(0, 0, 0)->addDay();
        $dayStartTime = $dayStartDate->timestamp;
        $dayEndTime = $dayEndDate->timestamp;
        //$delta = $startTime - $dayStartTime;
        //$roundedStartTime = $dayStartTime + (floor($delta/$steps) * $steps);
        
        $i = 0;
        $cycle = 0;
        $duration = config('manivelle.screens.timeline_interval');
        $itemsCount = sizeof($items);
        $timeline = [];
        $lastCycle = null;
        $lastCycleKey = null;
        $currentCycle = [
            'start' => null,
            'end' => null,
            'items' => []
        ];
        $cycleKey = [];
        $bubblesIndex = [];
        $bubbles = [];
        $bubbleIds = [];
        $currentTime = $dayStartTime;
        while ($currentTime < $dayEndTime) {
            $startDate = Carbon::createFromTimeStamp($currentTime);
            $endDate = Carbon::createFromTimeStamp($currentTime+$duration);
            if (!$currentCycle['start']) {
                $currentCycle['start'] = $startDate->timestamp;
            }
            $currentCycle['end'] = $endDate->timestamp;
            
            $item = self::getItemFromDate($items, $startDate, $currentCycle['items']);
            
            if ($item) {
                if (isset($item['bubbles'])) {
                    if (!isset($bubblesIndex[$item['id']])) {
                        $bubblesIndex[$item['id']] = 0;
                    }
                    $index = $bubblesIndex[$item['id']];
                    $bubble = $item['bubbles'][$index];
                    $bubblesIndex[$item['id']] = $index === sizeof($item['bubbles'])-1 ? 0:($index+1);
                } else {
                    $bubble = $item['bubble'];
                }
                $bubbleId = is_object($bubble) ? $bubble->id:$bubble;
                $bubbles[$bubbleId] = $bubble;
                $bubbleIds[$bubbleId] = $bubbleId;
                $cycleItem = [
                    'bubble_id' => $bubbleId,
                    'id' => $item['id'],
                    'duration' => $duration
                ];
                $currentCycle['items'][] = $cycleItem;
                $cycleKey[] = $item['id'].'|'.$bubbleId.'|'.$duration;
            }
            
            $i++;
            if ($i === $itemsCount) {
                $currentClycleKey = implode('-', $cycleKey);
                if ($lastCycleKey && $currentClycleKey === $lastCycleKey) {
                    $lastCycle['end'] = $endDate->timestamp;
                } else {
                    if ($lastCycle) {
                        $timeline[] = $lastCycle;
                    }
                    $lastCycleKey = $currentClycleKey;
                    $lastCycle = $currentCycle;
                }
                
                $i = 0;
                $currentCycle = [
                    'start' => null,
                    'end' => null,
                    'items' => []
                ];
                $cycleKey = [];
                $cycle++;
            }
            
            $currentTime += $duration;
        }
        
        if ($currentCycle['start']) {
            $currentClycleKey = implode('-', $cycleKey);
            if ($lastCycleKey && $currentClycleKey == $lastCycleKey) {
                $lastCycle['end'] = $endDate->timestamp;
                $timeline[] = $lastCycle;
            } else {
                $timeline[] = $lastCycle;
                $timeline[] = $currentCycle;
            }
        } else {
            $timeline[] = $lastCycle;
        }
        
        $cycles = Collection::make($timeline);
        
        $timeline = new Timeline();
        $timeline->cycles = $cycles;
        $timeline->bubbleIds = array_values($bubbleIds);
        return $timeline;
    }
    
    protected static function getTimelineItemsFromItems($items)
    {
        $timelineItems = [];
        $index = 0;
        foreach ($items as $item) {
            $bubble = $item->bubble;
            
            if (!$bubble || !$bubble->id) {
                continue;
            }
            
            $timelineItem = [
                'condition' => $item->condition,
                'id' => $index
            ];
            if ($bubble->type === 'filter') {
                $timelineItem['bubbles'] = self::getBubblesFromFilters($bubble->fields->filters);
                $timelineItem['bubblesIndex'] = 0;
            } else {
                $timelineItem['bubble'] = $bubble;
            }
            $timelineItems[] = $timelineItem;
            $index++;
        }
        return $timelineItems;
    }
    
    protected static function getBubblesFromFilters($filters)
    {
        $params = [];
        foreach ($filters as $filter) {
            if (isset($filter['value'])) {
                if ($filter['name'] === 'channel_id') {
                    $params[$filter['name']] = $filter['value'];
                } else {
                    $params['filter_'.$filter['name']] = $filter['value'];
                }
            }
        }
        
        $resource = ManivelleFacade::resource('bubbles');
        return $resource->query($params)->lists('bubbles.id');
    }
    
    protected static function getItemFromDate($items, $date, $currentCycleItems)
    {
        $validItems = self::getValidItems($items, $date);
        $cycleItemsCount = sizeof($currentCycleItems);
        
        if (!isset($validItems[$cycleItemsCount])) {
            return null;
        }
        
        //Sort by priority
        /*usort($validItems, function($a, $b)
        {
            if($a['priority'] === $b['priority'])
            {
                if($a['index'] === $b['index'])
                {
                    return 0;
                }
                return $a['index'] > $b['index'] ? 1:-1;
            }
            return $a['priority'] > $b['priority'] ? -1:1;
        });*/
        
        //Check if item is already there
        $item = $validItems[$cycleItemsCount];
        $id = $item['id'];
        $currentItem = array_first($currentCycleItems, function ($key, $item) use ($id) {
            return $item['id'] === $id;
        });
        
        return !$currentItem ? $item:null;
    }
    
    protected static function getValidItems($items, $date)
    {
        $validItems = [];
        foreach ($items as $item) {
            $condition = $item['condition'];
            if (!$condition || $condition->isValidWithDate($date)) {
            //$item['priority'] = $condition->getPriority($date);
                $validItems[] = $item;
            }
        }
        
        return $validItems;
    }
}
