<?php namespace Manivelle\GraphQL\Type;

use GraphQL;
use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\Type as GraphQLType;

class PlaylistItemType extends GraphQLType
{
    protected $attributes = [
        'name' => 'PlaylistItem',
        'description' => 'A playlist item'
    ];
    
    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The id of the bubble.'
            ],
            'order' => [
                'type' => Type::int(),
                'description' => 'The type of bubble'
            ],
            'bubble' => [
                'type' => GraphQL::type('Bubble'),
                'description' => 'The condition of a bubble'
            ],
            'condition' => [
                'type' => GraphQL::type('Condition'),
                'description' => 'The condition of a bubble'
            ]
        ];
    }
}
