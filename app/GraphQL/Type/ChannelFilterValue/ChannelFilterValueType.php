<?php namespace Manivelle\GraphQL\Type\ChannelFilterValue;

use GraphQL;
use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\Type as GraphQLType;

class ChannelFilterValueType extends GraphQLType
{
    public function attributes()
    {
        return [
            'name' => 'ChannelFilterValue',
            'description' => 'Channel filter value'
        ];
    }
    
    public function fields()
    {
        $fields = [
            'label' => [
                'type' => Type::string(),
                'description' => 'The label of the filter value'
            ],
            'value' => [
                'type' => Type::string(),
                'description' => 'The value of the filter value'
            ]
        ];
        
        return $fields;
    }
    
    public function interfaces()
    {
        return [
            GraphQL::type('ChannelFilterValueInterface')
        ];
    }
}
