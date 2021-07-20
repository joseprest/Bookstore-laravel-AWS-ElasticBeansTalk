<?php namespace Manivelle\GraphQL\Type;

use GraphQL;
use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\Type as GraphQLType;

class TimelineType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Timeline',
        'description' => 'A timeline'
    ];
    
    public function fields()
    {
        return [
            'cycles' => [
                'type' => Type::listOf(GraphQL::type('TimelineCycle')),
                'description' => 'The cycles'
            ],
            'bubbles' => [
                'type' => Type::listOf(GraphQL::type('Bubble')),
                'description' => 'The bubbles'
            ]
        ];
    }
}
