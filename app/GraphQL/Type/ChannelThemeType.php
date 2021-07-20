<?php namespace Manivelle\GraphQL\Type;

use GraphQL;
use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\Type as GraphQLType;

class ChannelThemeType extends GraphQLType
{
    protected $attributes = [
        'name' => 'ChannelTheme',
        'description' => 'A channel theme'
    ];
    
    public function fields()
    {
        
        return [
            'color_light' => [
                'type' => Type::string(),
                'description' => 'The color of channel'
            ],
            'color_medium' => [
                'type' => Type::string(),
                'description' => 'The color of channel'
            ],
            'color_normal' => [
                'type' => Type::string(),
                'description' => 'The color of channel'
            ],
            'color_dark' => [
                'type' => Type::string(),
                'description' => 'The color of channel'
            ],
            'color_darker' => [
                'type' => Type::string(),
                'description' => 'The color of channel'
            ],
            'color_shadow' => [
                'type' => Type::string(),
                'description' => 'The color of channel'
            ],
            'color_shadow_darker' => [
                'type' => Type::string(),
                'description' => 'The color of channel'
            ]
        ];
    }
}
