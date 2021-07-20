<?php namespace Manivelle\GraphQL\Type;

use GraphQL;
use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\Type as GraphQLType;

class FilterType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Filter',
        'description' => 'A filter'
    ];
    
    public function fields()
    {
        return [
            'name' => [
                'type' => Type::string(),
                'description' => 'The filter name'
            ],
            'value' => [
                'type' => Type::string(),
                'description' => 'The filter value',
                'resolve' => function ($root) {
                    $value = array_get((array)$root, 'value');
                    return is_array($value) ? implode(',', $value):$value;
                }
            ],
            'values' => [
                'type' => Type::listOf(Type::string()),
                'description' => 'The filter value',
                'resolve' => function ($root) {
                    $value = array_get((array)$root, 'value');
                    return (array)$value;
                }
            ]
        ];
    }
}
