<?php namespace Manivelle\GraphQL\Type;

use GraphQL;
use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\Type as GraphQLType;

class BubblesIdsType extends GraphQLType
{
    protected $attributes = [
        'name' => 'BubblesIds',
        'description' => 'Bubbles ids'
    ];
    
    public function fields()
    {
        return [
            'ids' => [
                'type' => Type::listOf(Type::string()),
                'description' => 'The id',
                'resolve' => function ($items) {
                    return $items;
                }
            ]
        ];
    }
}
