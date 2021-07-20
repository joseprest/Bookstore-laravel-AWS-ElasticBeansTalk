<?php namespace Manivelle\GraphQL\Type;

use GraphQL;
use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\Type as GraphQLType;

class ChannelFieldType extends GraphQLType
{
    protected $attributes = [
        'name' => 'ChannelField',
        'description' => 'A bubble field'
    ];
    
    public function fields()
    {
        return [
            'type' => [
                'type' => Type::string(),
                'description' => 'The type of bubble'
            ],
            'value' => [
                'type' => Type::string(),
                'description' => 'The value of field'
            ]
        ];
    }
}
