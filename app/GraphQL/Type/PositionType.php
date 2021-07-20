<?php namespace Manivelle\GraphQL\Type;

use GraphQL;
use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\Type as GraphQLType;

class PositionType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Position',
        'description' => 'A position'
    ];
    
    public function fields()
    {
        return [
            'latitude' => [
                'type' => Type::string(),
                'description' => 'The title of the snippet'
            ],
            'longitude' => [
                'type' => Type::string(),
                'description' => 'The subtitle of the snippet'
            ]
        ];
    }
}
