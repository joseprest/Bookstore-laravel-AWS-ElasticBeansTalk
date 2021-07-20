<?php namespace Manivelle\GraphQL\Query;

use GraphQL;
use GraphQL\Type\Definition\Type;

class Organisation extends ResourceQuery
{
    protected $resource = 'organisations';
    
    protected $attributes = [
        'description' => 'Organisation query'
    ];
    
    public function type()
    {
        return GraphQL::type('Organisation');
    }
    
    public function args()
    {
        return [
            'id' => ['name' => 'id', 'type' => Type::string()],
            'slug' => ['name' => 'slug', 'type' => Type::string()]
        ];
    }
}
