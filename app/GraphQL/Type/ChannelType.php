<?php namespace Manivelle\GraphQL\Type;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as GraphQLType;

use Manivelle\Models\ChannelPivot;

class ChannelType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Channel',
        'description' => 'A channel'
    ];
    
    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The id of the channel.'
            ],
            'type' => [
                'type' => Type::string(),
                'description' => 'The type of channel'
            ],
            'handle' => [
                'type' => Type::string(),
                'description' => 'The handle of channel'
            ],
            'snippet' => '\Manivelle\GraphQL\Field\SnippetField',
            'filters' => '\Manivelle\GraphQL\Field\ChannelFiltersField',
            'bubbles_filters' => '\Manivelle\GraphQL\Field\ChannelBubblesFiltersField',
            'fields' => [
                'type' => GraphQL::type('ChannelFieldsInterface'),
                'description' => 'The fields of a channel',
                'resolve' => function ($item) {
                    return $item;
                }
            ],
            'screen_settings' => [
                'type' => GraphQL::type('ScreenChannelSettings'),
                'description' => 'The screen settings of a channel',
                'resolve' => function ($item) {
                    return $item instanceof ChannelPivot ? $item->settings:null;
                }
            ],
            'bubbles' => 'Manivelle\GraphQL\Field\BubblesField',
            'bubbles_ids' => 'Manivelle\GraphQL\Field\BubblesIdsField'
        ];
    }
}
