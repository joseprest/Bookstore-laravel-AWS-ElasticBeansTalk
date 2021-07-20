<?php namespace Manivelle\GraphQL\Type\ChannelSettings;

use GraphQL;
use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\Type as GraphQLType;

class BubbleDetailsContentColumnType extends GraphQLType
{
    protected $attributes = [
        'name' => 'BubbleDetailsContentColumn',
        'description' => 'A channel theme'
    ];
    
    public function fields()
    {
        return [
            'type' => [
                'type' => Type::string(),
                'description' => 'The color of channel'
            ],
            'value' => [
                'type' => Type::string(),
                'description' => 'The color of channel'
            ],
            'column' => [
                'type' => Type::int(),
                'description' => 'The color of channel'
            ]
        ];
    }
}
