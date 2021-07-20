<?php namespace Manivelle\GraphQL\Type;

use GraphQL;
use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\Type as GraphQLType;

class BubblesPaginatedType extends GraphQLType
{
    protected $attributes = [
        'name' => 'BubblesPaginated',
        'description' => 'Paginated bubbles'
    ];
    
    public function fields()
    {
        return [
            'pagination' => [
                'type' => GraphQL::type('Pagination'),
                'description' => 'The id of the bubble.',
                'resolve' => function ($items) {
                    return [
                        'total'         => $items->total(),
                        'per_page'      => $items->perPage(),
                        'current_page'  => $items->currentPage(),
                        'last_page'     => $items->lastPage(),
                        'from'          => $items->firstItem(),
                        'to'            => $items->lastItem(),
                    ];
                }
            ],
            'items' => [
                'type' => Type::listOf(GraphQL::type('Bubble')),
                'description' => 'The items',
                'resolve' => function ($items) {
                    return $items->getCollection();
                }
            ]
        ];
    }
}
