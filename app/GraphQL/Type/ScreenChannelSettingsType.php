<?php namespace Manivelle\GraphQL\Type;

use GraphQL;
use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\Type as GraphQLType;

class ScreenChannelSettingsType extends GraphQLType
{
    protected $attributes = [
        'name' => 'ScreenChannelSettings',
        'description' => 'A screen channel settings'
    ];
    
    public function fields()
    {
        return [
            'filters' => [
                'type' => Type::listOf(GraphQL::type('Filter')),
                'description' => 'Filters'
            ]
        ];
    }
}
