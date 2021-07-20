<?php namespace Manivelle\GraphQL\Type;

use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\Type as GraphQLType;

class OrganisationType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Organisation',
        'description' => 'An organisation'
    ];
    
    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The id of the organisation.'
            ],
            'name' => [
                'type' => Type::string(),
                'description' => 'The name of the organisation.'
            ],
            'slug' => [
                'type' => Type::string(),
                'description' => 'The slug of the organisation.'
            ],
            'screens' => 'Manivelle\GraphQL\Field\ScreensField',
            'playlists' => 'Manivelle\GraphQL\Field\PlaylistsField'
        ];
    }
}
