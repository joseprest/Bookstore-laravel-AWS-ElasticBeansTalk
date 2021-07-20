<?php namespace Manivelle\GraphQL\Query;

use GraphQL;
use GraphQL\Type\Definition\Type;

class Conditions extends ResourcesQuery
{
    protected $resource = 'conditions';
    
    protected $attributes = [
        'description' => 'Conditions query'
    ];
    
    public function type()
    {
        return Type::listOf(GraphQL::type('Condition'));
    }
}
