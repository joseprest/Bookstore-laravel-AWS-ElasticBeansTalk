<?php namespace Manivelle\GraphQL\Type;

use GraphQL;
use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\Type as GraphQLType;

class BubblesIdsPaginatedType extends BubblesPaginatedType
{
    protected $attributes = [
        'name' => 'BubblesIdsPaginated',
        'description' => 'Paginated bubbles ids'
    ];
    
    public function fields()
    {
        $fields = parent::fields();
        unset($fields['items']);
        
        $fields['ids'] = [
            'type' => Type::listOf(Type::string()),
            'description' => 'The id',
            'resolve' => function ($items) {
                return $items->items();
            }
        ];
        
        return $fields;
    }
}
