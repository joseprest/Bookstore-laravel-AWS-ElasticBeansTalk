<?php namespace Manivelle\GraphQL\Type;

use GraphQL;
use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\InterfaceType as GraphQLInterfaceType;

class BubbleFieldsInterface extends GraphQLInterfaceType
{
    protected $attributes = [
        'name' => 'BubbleFieldsInterface',
        'description' => 'Fields'
    ];
    
    public function resolveType($root)
    {
        try {
            $name = 'Bubble'.studly_case($root['item']->type).'Fields';
            return GraphQL::type($name);
        } catch (\Exception $e) {
            return GraphQL::type('BubbleFields');
        }
    }
}
