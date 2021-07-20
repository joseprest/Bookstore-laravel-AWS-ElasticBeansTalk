<?php namespace Manivelle\GraphQL\Type;

use GraphQL;
use Image;
use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\Type as GraphQLType;

class PictureType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Picture',
        'description' => 'An picture'
    ];
    
    public function fields()
    {
        return [
            'width' => [
                'type' => Type::int(),
                'description' => 'The width of the image'
            ],
            'height' => [
                'type' => Type::int(),
                'description' => 'The height of the image'
            ],
            'link' => [
                'type' => Type::string(),
                'description' => 'The link of the image'
            ]
        ];
    }
}
