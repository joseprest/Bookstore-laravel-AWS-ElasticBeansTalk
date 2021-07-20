<?php namespace Manivelle\GraphQL\Type;

use GraphQL;
use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\InterfaceType as GraphQLInterfaceType;

class BubbleFieldInterface extends GraphQLInterfaceType
{
    protected $attributes = [
        'name' => 'BubbleFieldInterface',
        'description' => 'A bubble field interface'
    ];
    
    public function fields()
    {
        return [
            'type' => [
                'type' => Type::string(),
                'description' => 'The type of bubble'
            ],
            'label' => [
                'type' => Type::string(),
                'description' => 'The label of field'
            ],
            'value' => [
                'type' => Type::string(),
                'description' => 'The value of field'
            ]
        ];
    }
    
    public function resolveType($item)
    {
        try {
            $name = 'Bubble'.studly_case($item['type']).'Field';
            return GraphQL::type($name);
        } catch (\Exception $e) {
            return GraphQL::type('BubbleField');
        }
    }
}
