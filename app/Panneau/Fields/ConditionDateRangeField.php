<?php namespace Manivelle\Panneau\Fields;

use Panneau\Fields\DateRange;

class ConditionDateRangeField extends DateRange
{
    
    protected $attributes = [
        'type' => 'condition_daterange',
        'attributes' => [
            'type' => 'date'
        ]
    ];
    
    public function save($data, $pivotData = [])
    {
        $objectData = [
            'start' => array_get($data, '0', null),
            'end' => array_get($data, '1', null)
        ];
        
        return parent::save($objectData, $pivotData);
    }
    
    public function value()
    {
        $value = parent::value();
        
        if (!$value->start && !$value->end) {
            return null;
        }
        
        $array = [
            $value->start ? $value->start:'',
            $value->end ? $value->end:''
        ];
        
        return $array;
    }
}
