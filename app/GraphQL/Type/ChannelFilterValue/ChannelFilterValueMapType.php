<?php namespace Manivelle\GraphQL\Type\ChannelFilterValue;

use GraphQL;
use GraphQL\Type\Definition\Type;

class ChannelFilterValueMapType extends ChannelFilterValueType
{
    public function attributes()
    {
        return [
            'name' => 'ChannelFilterValueMap',
            'description' => 'Channel filter map value'
        ];
    }
    
    public function fields()
    {
        $fields = parent::fields();
        
        $fields['position'] = [
            'type' => GraphQL::type('Position'),
            'description' => 'Position'
        ];
        
        $fields['city'] = [
            'type' => Type::string(),
            'description' => 'City'
        ];
        
        $fields['region'] = [
            'type' => Type::string(),
            'description' => 'Region'
        ];
        
        return $fields;
    }
}
