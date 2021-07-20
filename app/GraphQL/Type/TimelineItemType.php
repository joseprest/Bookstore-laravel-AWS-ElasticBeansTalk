<?php namespace Manivelle\GraphQL\Type;

use GraphQL;
use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\Type as GraphQLType;

class TimelineItemType extends GraphQLType
{
    protected $attributes = [
        'name' => 'TimelineItem',
        'description' => 'A timeline item'
    ];
    
    public function fields()
    {
        return [
            'id' => [
                'type' => Type::string(),
                'description' => 'The id'
            ],
            'bubble_id' => [
                'type' => Type::string(),
                'description' => 'The id of the bubble'
            ],
            'duration' => [
                'type' => Type::int(),
                'description' => 'The duration'
            ]
        ];
    }
}
