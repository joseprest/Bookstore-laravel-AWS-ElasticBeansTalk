<?php namespace Manivelle\GraphQL\Query;

use GraphQL;
use GraphQL\Type\Definition\Type;

class Organisations extends ResourcesQuery
{
    protected $resource = 'organisations';
    
    protected $attributes = [
        'description' => 'Organisations query'
    ];
    
    public function type()
    {
        return Type::listOf(GraphQL::type('Organisation'));
    }
}
