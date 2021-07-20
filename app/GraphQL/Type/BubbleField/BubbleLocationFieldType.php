<?php namespace Manivelle\GraphQL\Type\BubbleField;

use GraphQL;
use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\Type as GraphQLType;

class BubbleLocationFieldType extends GraphQLType
{
    protected $attributes = [
        'name' => 'BubbleLocationField',
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
                    return array_get($root, 'value.name');
                }
            ],
            'position' => [
                'type' => GraphQL::type('Position'),
                'description' => 'The position of field',
                'resolve' => function ($root) {
                    return array_get($root, 'value.position');
                }
            ],
            'city' => [
                'type' => Type::string(),
                'description' => 'The city of field',
                'resolve' => function ($root) {
                    return array_get($root, 'value.city');
                }
            ],
            'region' => [
                'type' => Type::string(),
                'description' => 'The region of field',
                'resolve' => function ($root) {
                    return array_get($root, 'value.region');
                }
            ],
            'address' => [
                'type' => Type::string(),
                'description' => 'The address of field',
                'resolve' => function ($root) {
                    return array_get($root, 'value.address');
                }
            ],
            'postalcode' => [
                'type' => Type::string(),
                'description' => 'The postalcode of field',
                'resolve' => function ($root) {
                    return array_get($root, 'value.postalcode');
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
