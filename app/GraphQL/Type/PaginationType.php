<?php namespace Manivelle\GraphQL\Type;

use GraphQL;
use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\Type as GraphQLType;

class PaginationType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Pagination',
        'description' => 'A pagination'
    ];
    
    public function fields()
    {
        return [
            'total' => [
                'type' => Type::int(),
                'description' => 'The total number of items'
            ],
            'per_page' => [
                'type' => Type::int(),
                'description' => 'The number of items per page'
            ],
            'current_page' => [
                'type' => Type::int(),
                'description' => 'The current page'
            ],
            'last_page' => [
                'type' => Type::int(),
                'description' => 'The last page'
            ],
            'from' => [
                'type' => Type::int(),
                'description' => 'The first item'
            ],
            'to' => [
                'type' => Type::int(),
                'description' => 'The last item'
            ]
        ];
    }
}
