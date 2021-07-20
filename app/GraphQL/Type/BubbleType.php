<?php namespace Manivelle\GraphQL\Type;

use GraphQL;
use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\Type as GraphQLType;

class BubbleType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Bubble',
        'description' => 'A bubble'
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The id of the bubble.'
            ],
            'type' => [
                'type' => Type::string(),
                'description' => 'The type of bubble'
            ],
            'type_name' => [
                'type' => Type::string(),
                'description' => 'The type of bubble',
                'resolve' => function ($item) {
                    return $item->bubbleType()->label;
                }
            ],
            'channel_id' => [
                'type' => Type::string(),
                'description' => 'The id of the channel of bubble',
                'resolve' => function ($item) {
                    return !$item->channels->isEmpty() ? $item->channels[0]->id:null;
                }
            ],
            'snippet' => '\Manivelle\GraphQL\Field\SnippetField',
            'filters' => [
                'type' => GraphQL::type('BubbleFiltersInterface'),
                'description' => 'The filters of bubble',
                'resolve' => function ($item) {
                    return $item;
                }
            ],
            'fields' => [
                'type' => GraphQL::type('BubbleFieldsInterface'),
                'description' => 'The fields of bubble',
                'resolve' => function ($item) {
                    return [
                        'item' => $item,
                        'fields' => $item->fields->toArray()
                    ];
                }
            ],
            'suggestions' => [
                'type' => Type::listOf(Type::string()),
                'description' => 'The suggestions of bubble',
                'resolve' => function ($item) {
                    // return $item->getSuggestions();
                    return [];
                }
            ]
        ];
    }
}
