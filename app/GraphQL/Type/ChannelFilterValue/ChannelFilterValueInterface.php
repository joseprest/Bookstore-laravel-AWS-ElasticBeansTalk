<?php namespace Manivelle\GraphQL\Type\ChannelFilterValue;

use GraphQL;
use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\InterfaceType as GraphQLInterfaceType;

class ChannelFilterValueInterface extends GraphQLInterfaceType
{
    protected $attributes = [
        'name' => 'ChannelFilterValueInterface',
        'description' => 'Filter value interface'
    ];
    
    public function fields()
    {
        return [
            'value' => [
                'type' => Type::string(),
                'description' => 'The name of filter'
            ],
            'label' => [
                'type' => Type::string(),
                'description' => 'The label of filter'
            ]
        ];
    }
    
    public function resolveType($item)
    {
        try {
            $name = 'ChannelFilterValue'.studly_case($item['type']);
            return GraphQL::type($name);
        } catch (\Exception $e) {
            return GraphQL::type('ChannelFilterValue');
        }
    }
}
