<?php namespace Manivelle\GraphQL\Type;

use GraphQL;
use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\InterfaceType as GraphQLInterfaceType;

class BubbleFiltersInterface extends GraphQLInterfaceType
{
    protected $attributes = [
        'name' => 'BubbleFiltersInterface',
        'description' => 'Filters'
    ];
    
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
    
    public function fields()
    {
        return [
            
        ];
    }
    
    public function resolveType($item)
    {
        try {
            $name = 'Bubble'.studly_case($item->type).'Filters';
            return GraphQL::type($name);
        } catch (\Exception $e) {
            return GraphQL::type('BubbleFilters');
        }
    }
}
