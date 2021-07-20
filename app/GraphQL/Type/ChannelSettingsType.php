<?php namespace Manivelle\GraphQL\Type;

use GraphQL;
use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\Type as GraphQLType;

class ChannelSettingsType extends GraphQLType
{
    protected $attributes = [
        'name' => 'ChannelSettings',
        'description' => 'A channel theme',
    ];

    public function fields()
    {
        return [
            'channelView' => [
                'type' => Type::string(),
                'description' => 'The channel view',
            ],
            'channelMarkerType' => [
                'type' => Type::string(),
                'description' => 'The marker type',
            ],

            'canAddBubbles' => [
                'type' => Type::boolean(),
                'description' => 'Can add bubbles',
                'resolve' => function ($root) {
                    return (bool) array_get((array) $root, 'canAddBubbles', false);
                },
            ],

            'bubblesByOrganisation' => [
                'type' => Type::boolean(),
                'description' => 'Bubbles are filtered by organisation',
                'resolve' => function ($root) {
                    return (bool) array_get((array) $root, 'bubblesByOrganisation', false);
                },
            ],

            'colorPalette' => [
                'type' => Type::string(),
                'description' => 'Color palette to use in this channel',
            ],

            'randomPositionCards' => [
                'type' => Type::boolean(),
                'description' => 'Random position cards',
                'resolve' => function ($root) {
                    return (bool) array_get((array) $root, 'randomPositionCards', true);
                },
            ],

            'channelFilterName' => [
                'type' => Type::string(),
                'description' => 'The color of channel',
            ],

            'slideshowInfosView' => [
                'type' => Type::string(),
                'description' => 'The color of channel',
            ],

            'slideshowImageMaxWidth' => [
                'type' => Type::float(),
                'description' => 'The color of channel',
            ],

            'slidesHeightRatio' => [
                'type' => Type::float(),
                'description' => 'The color of channel',
            ],
            'slidesMarginRatio' => [
                'type' => Type::float(),
                'description' => 'The color of channel',
            ],
            'slidesWidthRatio' => [
                'type' => Type::float(),
                'description' => 'The color of channel',
            ],
            'slidesSlideView' => [
                'type' => Type::string(),
                'description' => 'The color of channel',
            ],
            'slideMenuDestinationView' => [
                'type' => Type::string(),
                'description' => 'The color of channel',
            ],

            'modalBubblesListView' => [
                'type' => Type::string(),
                'description' => 'The color of channel',
            ],

            'modalSendBubbleHasMessage' => [
                'type' => Type::boolean(),
                'description' => 'The color of channel',
                'resolve' => function ($root) {
                    return (bool) array_get((array) $root, 'modalSendBubbleHasMessage', false);
                },
            ],
            'modalSendBubbleDefaultMessage' => [
                'type' => Type::string(),
                'description' => 'The color of channel',
            ],

            'bubbleDetailsExcludedButtons' => [
                'type' => Type::listOf(Type::string()),
                'description' => 'The buttons in details pan',
            ],
            'bubbleDetailsShowTypeName' => [
                'type' => Type::boolean(),
                'description' => 'The color of channel',
            ],
            'bubbleDetailsShowTitle' => [
                'type' => Type::boolean(),
                'description' => 'The color of channel',
            ],
            'bubbleDetailsContentView' => [
                'type' => Type::string(),
                'description' => 'The color of channel',
            ],
            'bubbleDetailsContentColumns' => [
                'type' => Type::listOf(GraphQL::type('ChannelSettingsBubbleDetailsContentColumn')),
                'description' => 'The color of channel',
            ],

            'bubbleSuggestionView' => [
                'type' => Type::string(),
                'description' => 'The color of channel',
            ],
        ];
    }
}
