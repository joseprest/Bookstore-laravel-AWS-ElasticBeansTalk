<?php namespace Manivelle\GraphQL\Type\BubbleField;

use GraphQL;
use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\Type as GraphQLType;

use Manivelle\Support\Str;

class BubbleDaterangeFieldType extends GraphQLType
{
    protected $attributes = [
        'name' => 'BubbleDaterangeField',
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
                    return Str::formatDaterange($root['value']['start'], $root['value']['end']);
                }
            ],
            'daterange' => [
                'type' => GraphQL::type('Daterange'),
                'description' => 'The start date',
                'resolve' => function ($root) {
                    return $root['value'];
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
