<?php namespace Manivelle\GraphQL\Query;

use GraphQL;
use GraphQL\Type\Definition\Type;

class Screens extends ResourcesQuery
{
    protected $resource = 'screens';
    
    protected $attributes = [
        'description' => 'Screens query'
    ];
    
    public function type()
    {
        return Type::listOf(GraphQL::type('Screen'));
    }
    
    public function args()
    {
        $args = parent::args();
        
        $args['organisation_id'] = ['name' => 'organisation_id', 'type' => Type::string()];
        $args['auth_code'] = ['name' => 'auth_code', 'type' => Type::string()];
        
        return $args;
    }
}
