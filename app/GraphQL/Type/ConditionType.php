<?php namespace Manivelle\GraphQL\Type;

use GraphQL;
use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\Type as GraphQLType;

class ConditionType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Condition',
        'description' => 'A condition'
    ];
    
    public function fields()
    {
        $fields = [
            'id' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The id of a condition.'
            ],
            'name' => [
                'type' => Type::string(),
                'description' => 'The name of a condition'
            ],
            'snippet' => '\Manivelle\GraphQL\Field\SnippetField',
            'fields' => [
                'type' => GraphQL::type('ConditionFields'),
                'description' => 'The fields of a condition',
                'resolve' => function ($item) {
                    return $item->fields;
                }
            ],
        ];
        
        return $fields;
    }
}
