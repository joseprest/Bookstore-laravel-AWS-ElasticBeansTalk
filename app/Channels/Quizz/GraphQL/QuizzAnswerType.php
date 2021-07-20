<?php namespace Manivelle\Channels\Quizz\GraphQL;

use GraphQL;
use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\Type as GraphQLType;

class QuizzAnswerType extends GraphQLType
{
    protected $attributes = [
        'name' => 'QuizzAnswer',
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
