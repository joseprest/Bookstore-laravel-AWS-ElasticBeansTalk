<?php namespace Manivelle\GraphQL\Type\ChannelBubbleFilter;

use GraphQL;
use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\Type as GraphQLType;

class ChannelBubbleFilterType extends GraphQLType
{
    
    public function attributes()
    {
        return [
            'name' => 'ChannelBubbleFilter',
            'description' => 'Channel bubble filter'
        ];
    }
    
    public function fields()
    {
        $fields = [
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
        
        return $fields;
    }
    
    public function interfaces()
    {
        return [
            GraphQL::type('ChannelBubbleFilterInterface')
        ];
    }
}
