<?php namespace Manivelle\GraphQL\Type;

use GraphQL;

use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\Type as GraphQLType;

class OrganisationInvitationType extends GraphQLType
{
    protected $attributes = [
        'name' => 'OrganisationInvitation',
        'description' => 'An invitation'
    ];
    
    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The id of the invitation.'
            ],
            'name' => [
                'type' => Type::string()
            ],
            'email' => [
                'type' => Type::string()
            ],
            'role' => \Manivelle\GraphQL\Field\RoleField::class
        ];
    }
    
    protected function resolveNameField($root)
    {
        return $root->email;
    }
    
    protected function resolveRoleField($root)
    {
        return $root->role ? $root->role->name:'';
    }
}
