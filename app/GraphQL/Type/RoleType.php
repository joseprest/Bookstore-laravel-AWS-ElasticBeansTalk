<?php namespace Manivelle\GraphQL\Type;

use GraphQL;

use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\Type as GraphQLType;

class RoleType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Role',
        'description' => 'A user role'
    ];
    
    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The id of a role'
            ],
            'name' => [
                'type' => Type::string(),
                'description' => 'The name of a role'
            ],
            'description' => [
                'type' => Type::string(),
                'description' => 'The description of a role'
            ]
        ];
    }
}
