<?php namespace Manivelle\GraphQL\Type;

use GraphQL;
use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\Type as GraphQLType;

class SelectValueType extends GraphQLType
{
    protected $attributes = [
        'name' => 'SelectValue',
        'description' => 'A select value'
    ];
    
    public function fields()
    {
        return [
            'label' => [
                'type' => Type::string(),
                'description' => 'The title of the snippet'
            ],
            'value' => [
                'type' => Type::string(),
                'description' => 'The subtitle of the snippet'
            ]
        ];
    }
}
