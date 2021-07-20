<?php namespace Manivelle\GraphQL\Type;

use GraphQL;
use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\Type as GraphQLType;

class BubbleFiltersType extends GraphQLType
{
    protected $bubbleType;
    
    public function attributes()
    {
        $name = $this->bubbleType ? ('Bubble'.studly_case($this->bubbleType->type).'Filters'):'BubbleFilters';
        return [
            'name' => $name,
            'description' => 'Bubble fields'
        ];
    }
    
    public function setBubbleType($bubbleType)
    {
        $this->bubbleType = $bubbleType;
    }
    
    public function fields()
    {
        $fields = [];
        if ($this->bubbleType) {
            $bubbleTypeFilters = $this->bubbleType->getFilters();
            $typeNamespace = $this->bubbleType->type;
            foreach ($bubbleTypeFilters as $field) {
                $name = $field['name'];
                $value = $field['value'];
                $multiple = array_get($field, 'multiple', false);
                
                $fields[$name] = [
                    'type' => $multiple ? Type::listOf(Type::string()):Type::string(),
                    'description' => 'The '.$name.' of the filter',
                    'resolve' => function ($item) use ($value) {
                        return $value($item, $item->fields);
                    }
                ];
            }
        }
        
        return $fields;
    }
    
    public function interfaces()
    {
        return [
            GraphQL::type('BubbleFiltersInterface')
        ];
    }
}
