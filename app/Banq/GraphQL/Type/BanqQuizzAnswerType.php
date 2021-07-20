<?php namespace Manivelle\Banq\GraphQL\Type;

use GraphQL;
use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\Type as GraphQLType;

class BanqQuizzAnswerType extends GraphQLType
{
    protected $attributes = [
        'name' => 'BanqQuizzAnswer',
        'description' => 'A quizz answer'
    ];
    
    public function fields()
    {
        return [
            'text' => [
                'type' => Type::string(),
                'description' => 'The text of the answer'
            ],
            'explanation' => [
                'type' => Type::string(),
                'description' => 'The explanation of the answer'
            ],
            'good' => [
                'type' => Type::boolean(),
                'description' => 'If it is the good answer'
            ]
        ];
    }
}
