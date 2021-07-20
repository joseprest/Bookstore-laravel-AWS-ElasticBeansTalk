<?php namespace Manivelle\GraphQL\Query;

use GraphQL;
use GraphQL\Type\Definition\Type;

class Users extends ResourcesQuery
{
    protected $resource = 'users';
    
    protected $attributes = [
        'description' => 'Users query'
    ];
    
    public function type()
    {
        return Type::listOf(GraphQL::type('User'));
    }
    
    public function args()
    {
        $args = parent::args();
        
        $args['organisation_id'] = ['name' => 'organisation_id', 'type' => Type::string()];
        
        return $args;
    }
}
