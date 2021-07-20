<?php namespace Manivelle\GraphQL\Type;

use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\Type as GraphQLType;

class ScreenCommandType extends GraphQLType
{
    protected $attributes = [
        'name' => 'ScreenCommand',
        'description' => 'A screen command'
    ];
    
    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The id of the screen command.'
            ],
            'command' => [
                'type' => Type::string(),
                'description' => 'The command'
            ],
            'arguments' => [
                'type' => Type::listOf(Type::string()),
                'description' => 'The arguments of the command'
            ],
            'payload' => [
                'type' => Type::string(),
                'description' => 'The payload of the command',
                'resolve' => function ($root) {
                    return json_encode($root->payload);
                }
            ],
            'output' => [
                'type' => Type::string(),
                'description' => 'The output of the command'
            ],
            'return_code' => [
                'type' => Type::int(),
                'description' => 'The return code'
            ],
            'executed' => [
                'type' => Type::boolean(),
                'description' => 'The execution state'
            ],
            'sended' => [
                'type' => Type::boolean(),
                'description' => 'The sended state'
            ],
            'executed_at' => [
                'type' => Type::string(),
                'description' => 'The execution date'
            ],
            'sended_at' => [
                'type' => Type::string(),
                'description' => 'The send date'
            ],
            'created_at' => [
                'type' => Type::string(),
                'description' => 'The creation date'
            ]
        ];
    }
}
