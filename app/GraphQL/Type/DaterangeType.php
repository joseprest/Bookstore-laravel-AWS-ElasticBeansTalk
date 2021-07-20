<?php namespace Manivelle\GraphQL\Type;

use GraphQL;
use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\Type as GraphQLType;

class DaterangeType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Daterange',
        'description' => 'A position'
    ];
    
    public function fields()
    {
        return [
            'start' => [
                'type' => Type::string(),
                'description' => 'The start date'
            ],
            'end' => [
                'type' => Type::string(),
                'description' => 'The end date'
            ]
        ];
    }
}
