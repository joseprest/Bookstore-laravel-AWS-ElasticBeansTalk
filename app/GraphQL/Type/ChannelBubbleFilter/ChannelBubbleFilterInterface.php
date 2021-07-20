<?php namespace Manivelle\GraphQL\Type\ChannelBubbleFilter;

use GraphQL;
use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\InterfaceType as GraphQLInterfaceType;

class ChannelBubbleFilterInterface extends GraphQLInterfaceType
{
    protected $attributes = [
        'name' => 'ChannelBubbleFilterInterface',
        'description' => 'Channel Bubble Filter interface'
    ];
    
    public function fields()
    {
        return [
            'name' => [
                'type' => Type::string(),
                'description' => 'The name of filter'
            ],
            'label' => [
                'type' => Type::string(),
                'description' => 'The label of filter'
            ],
            'type' => [
                'type' => Type::string(),
                'description' => 'The type of filter'
            ]
        ];
    }
    
    public function resolveType($item)
    {
        try {
            $type = isset($item['type']) ? studly_case($item['type']):'';
            $name = 'ChannelBubbleFilter'.$type;
            return GraphQL::type($name);
        } catch (\Exception $e) {
            return GraphQL::type('ChannelBubbleFilter');
        }
    }
}
