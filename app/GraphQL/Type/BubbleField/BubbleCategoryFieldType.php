<?php namespace Manivelle\GraphQL\Type\BubbleField;

use GraphQL;
use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\Type as GraphQLType;

class BubbleCategoryFieldType extends GraphQLType
{
    protected $attributes = [
        'name' => 'BubbleCategoryField',
        'description' => 'A bubble field'
    ];
    
    public function fields()
    {
        return [
            'type' => [
                'type' => Type::string(),
                'description' => 'The type of bubble',
                'resolve' => function ($root) {
                    return 'text';
                }
            ],
            'label' => [
                'type' => Type::string(),
                'description' => 'The label of field'
            ],
            'value' => [
                'type' => Type::string(),
                'description' => 'The value of field',
                'resolve' => function ($root) {
                    $value = array_get($root, 'value.name', null);
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
