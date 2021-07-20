<?php namespace Manivelle\GraphQL\Type\BubbleField;

use GraphQL;
use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\Type as GraphQLType;

use Manivelle\Support\Str;

class BubbleStringsFieldType extends GraphQLType
{
    protected $attributes = [
        'name' => 'BubbleStringsField',
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
                    $values = array_get($root, 'value', []);
                    return implode(', ', $values);
                }
            ],
            'values' => [
                'type' => Type::listOf(Type::string()),
                'description' => 'The values of field',
                'resolve' => function ($root) {
                    return array_get($root, 'value', []);
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
