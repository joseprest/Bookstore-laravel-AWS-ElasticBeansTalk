<?php namespace Manivelle\Banq\GraphQL\Type;

use GraphQL;
use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\Type as GraphQLType;

use Manivelle\Support\Str;

class BubbleBanqQuizzAnswersFieldType extends GraphQLType
{
    protected $attributes = [
        'name' => 'BubbleBanqQuizzAnswersField',
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
                    return null;
                }
            ],
            'answers' => [
                'type' => Type::listOf(GraphQL::type('BanqQuizzAnswer')),
                'description' => 'The values of field',
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
