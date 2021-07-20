<?php namespace Manivelle\GraphQL\Type;

use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\Type as GraphQLType;

class ScreenType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Screen',
        'description' => 'A screen'
    ];
    
    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The id of the screen.'
            ],
            'snippet' => '\Manivelle\GraphQL\Field\SnippetField',
            'playlists' => 'Manivelle\GraphQL\Field\PlaylistsField'
        ];
    }
}
