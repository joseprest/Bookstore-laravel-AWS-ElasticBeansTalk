<?php namespace Manivelle\GraphQL\Type;

use GraphQL;

use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\Type as GraphQLType;

class UserType extends GraphQLType
{
    protected $attributes = [
        'name' => 'User',
        'description' => 'A base user'
    ];
    
    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The id of the user.'
            ],
            'name' => [
                'type' => Type::string()
            ],
            'email' => [
                'type' => Type::string()
            ],
            'role' => \Manivelle\GraphQL\Field\RoleField::class,
            'avatar' => \Manivelle\GraphQL\Field\PictureField::class
        ];
    }
    
    protected function resolveRoleField()
    {
        return first($root->roles->toArray());
    }
}
