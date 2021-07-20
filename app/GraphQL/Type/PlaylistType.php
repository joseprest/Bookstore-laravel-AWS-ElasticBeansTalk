<?php namespace Manivelle\GraphQL\Type;

use GraphQL;
use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\Type as GraphQLType;

class PlaylistType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Playlist',
        'description' => 'A playlist'
    ];
    
    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The id of the screen.'
            ],
            'name' => [
                'type' => Type::string()
            ],
            'items' => [
                'type' => Type::listOf(GraphQL::type('PlaylistItem')),
                'description' => 'The items of a playlist.'
            ],
            'screens' => [
                'type' => Type::listOf(GraphQL::type('Screen')),
                'description' => 'The screens of a playlist.'
            ]
        ];
    }
}
