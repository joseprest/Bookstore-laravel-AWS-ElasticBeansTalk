<?php namespace Manivelle\GraphQL\Type;

use GraphQL;
use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\Type as GraphQLType;

class TimelineCycleType extends GraphQLType
{
    protected $attributes = [
        'name' => 'TimelineCycle',
        'description' => 'A timeline cycle'
    ];
    
    public function fields()
    {
        return [
            'start' => [
                'type' => Type::int(),
                'description' => 'The start timestamp'
            ],
            'end' => [
                'type' => Type::int(),
                'description' => 'The end timestamp'
            ],
            'items' => [
                'type' => Type::listOf(GraphQL::type('TimelineItem')),
                'description' => 'The cycle'
            ]
        ];
    }
}
