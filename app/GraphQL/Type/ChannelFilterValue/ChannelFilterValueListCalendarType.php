<?php namespace Manivelle\GraphQL\Type\ChannelFilterValue;

use GraphQL;
use GraphQL\Type\Definition\Type;

class ChannelFilterValueListCalendarType extends ChannelFilterValueType
{
    public function attributes()
    {
        return [
            'name' => 'ChannelFilterValueListCalendar',
            'description' => 'Channel filter value'
        ];
    }
    
    public function fields()
    {
        $fields = parent::fields();
        
        $fields['date'] = [
            'type' => Type::string(),
            'description' => 'Date value'
        ];
        
        return $fields;
    }
}
