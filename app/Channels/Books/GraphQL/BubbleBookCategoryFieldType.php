<?php namespace Manivelle\Channels\Books\GraphQL;

use GraphQL;
use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\Type as GraphQLType;

class BubbleBookCategoryFieldType extends GraphQLType
{
    protected $attributes = [
        'name' => 'BubbleBookCategoryField',
        'description' => 'A bubble field'
    ];
    
    public function fields()
    {
        return [
            'type' => [
                'type' => Type::string(),
                'description' => 'The type of bubble'
            ],
            'label' => [
                'type' => Type::string(),
                'description' => 'The label of field'
            ],
            'value' => [
                'type' => Type::string(),
                'description' => 'The value of field',
                'resolve' => function ($root) {
                    $category = $root['value'];
                    $categories = trans('books.categories');
                    $value = array_first($categories, function ($key, $value) use ($category) {
                        return $key === $category;
                    });
                    
                    return is_string($value) || $value === null ? $value:null;
                }
            ]
        ];
    }
    
    public function interfaces()
    {
        return [
            GraphQL::type('BubbleFieldInterface')
        ];
    }
}
