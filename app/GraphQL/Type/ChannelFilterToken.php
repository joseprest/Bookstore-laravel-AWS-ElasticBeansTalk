<?php namespace Manivelle\GraphQL\Type;

use GraphQL;
use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\Type as GraphQLType;

class ChannelFilterToken extends GraphQLType
{
    protected $attributes = [
        'name' => 'ChannelFilterToken',
        'description' => 'A filter token'
    ];
    
    public function fields()
    {
        
        return [
            'label' => [
                'type' => Type::string(),
                'description' => 'The color of channel',
                'resolve' => function ($root) {
                    return is_string($root) ? $root:$root['label'];
                }
            ],
            'value' => [
                'type' => Type::string(),
                'description' => 'The color of channel',
                'resolve' => function ($root) {
                    return is_string($root) ? $root:$root['value'];
                }
            ]
        ];
    }
}
