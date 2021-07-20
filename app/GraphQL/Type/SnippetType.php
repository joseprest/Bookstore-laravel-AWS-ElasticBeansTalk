<?php namespace Manivelle\GraphQL\Type;

use GraphQL;
use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\Type as GraphQLType;

class SnippetType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Snippet',
        'description' => 'A snippet'
    ];

    public function fields()
    {
        return [
            'title' => [
                'type' => Type::string(),
                'description' => 'The title of the snippet'
            ],
            'subtitle' => [
                'type' => Type::string(),
                'description' => 'The subtitle of the snippet'
            ],
            'type' => [
                'type' => Type::string(),
                'description' => 'The type of the snippet'
            ],
            'summary' => [
                'type' => Type::string(),
                'description' => 'The summary of the snippet'
            ],
            'link' => [
                'type' => Type::string(),
                'description' => 'The link of the snippet'
            ],
            'description' => [
                'type' => Type::string(),
                'description' => 'The description of the snippet'
            ],
            'picture' => \Manivelle\GraphQL\Field\PictureField::class
        ];
    }
}
