<?php namespace Manivelle\GraphQL\Type\ChannelFilter;

use GraphQL;
use Closure;
use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\Type as GraphQLType;

class ChannelFilterType extends GraphQLType
{
    
    public function attributes()
    {
        return [
            'name' => 'ChannelFilter',
            'description' => 'Channel filter'
        ];
    }
    
    public function fields()
    {
        $fields = [
            'name' => [
                'type' => Type::string(),
                'description' => 'The name of filter'
            ],
            'label' => [
                'type' => Type::string(),
                'description' => 'The label of filter'
            ],
            'type' => [
                'type' => Type::string(),
                'description' => 'The type of filter'
            ],
            'values' => [
                'type' => Type::listOf(GraphQL::type('ChannelFilterValueInterface')),
                'description' => 'The value of filter',
                'resolve' => function ($root) {
                    if (isset($root['values'])) {
                        if (is_callable($root['values'])) {
                            $values = $root['values']();
                        } else {
                            $values = $root['values'];
                        }
                    } else {
                        $values = [];
                    }
                    $items = [];
                    foreach ($values as $value) {
                        $type = [$root['type']];
                        if (isset($root['layout'])) {
                            $type[] = $root['layout'];
                        }
                        if (!is_array($value)) {
                            $value = [
                                'label' => $value,
                                'value' => $value
                            ];
                        }
                        $value['type'] = implode('_', $type);
                        $value['filter'] = $root;
                        $items[] = $value;
                    }
                    return $items;
                }
            ]
        ];
        
        return $fields;
    }
    
    public function interfaces()
    {
        return [
            GraphQL::type('ChannelFilterInterface')
        ];
    }
}
