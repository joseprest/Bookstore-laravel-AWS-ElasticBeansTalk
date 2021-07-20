<?php namespace Manivelle\GraphQL\Type\ChannelFilter;

use GraphQL;
use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\InterfaceType as GraphQLInterfaceType;

class ChannelFilterInterface extends GraphQLInterfaceType
{
    protected $attributes = [
        'name' => 'ChannelFilterInterface',
        'description' => 'Filter interface'
    ];
    
    public function fields()
    {
        return [
            'name' => [
                'type' => Type::string(),
                'description' => 'The name of filter'
            ],
            'label' => [
                'type' => Type::string(),
                'description' => 'The label of filter'
            ],
            'type' => [
                'type' => Type::string(),
                'description' => 'The type of filter'
            ],
            'values' => [
                'type' => Type::listOf(GraphQL::type('ChannelFilterValueInterface')),
                'description' => 'The value of filter'
            ]
        ];
    }
    
    public function resolveType($item)
    {
        try {
            $name = 'ChannelFilter'.studly_case($item['type']);
            return GraphQL::type($name);
        } catch (\Exception $e) {
            return GraphQL::type('ChannelFilter');
        }
    }
}
