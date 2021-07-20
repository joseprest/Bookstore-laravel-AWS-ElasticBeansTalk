<?php namespace Manivelle\GraphQL\Type\BubbleField;

use GraphQL;
use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\Type as GraphQLType;

use Panneau\Fields\Text;
use Panneau\Fields\MetadataString;
use Panneau\Fields\MetadataStrings;

class BubbleFieldType extends GraphQLType
{
    protected $attributes = [
        'name' => 'BubbleField',
        'description' => 'A bubble field'
    ];
    
    public function fields()
    {
        return [
            'type' => [
                'type' => Type::string(),
                'description' => 'The type of bubble',
                'resolve' => function ($item) {
                    $field = $item['field'];
                    return $field instanceof Text || $field instanceof MetadataString || $field instanceof MetadataStrings ? 'text':$item['type'];
                }
            ],
            'value' => [
                'type' => Type::string(),
                'description' => 'The value of field',
                'resolve' => function ($item) {
                    $value = $item['value'];
                    if (is_array($value)) {
                        $value = array_get($value, '0.name') ? array_pluck($value, 'name'):array_get($value, 'name', $value);
                        return implode(', ', $value);
                    } elseif (!is_string($value)) {
                        return isset($value['name']) ? $value['name']:'';
                    }
                    return $value;
                }
            ],
            'label' => [
                'type' => Type::string(),
                'description' => 'The label of field'
            ]
        ];
    }
    
    public function interfaces()
    {
        return [
            GraphQL::type('BubbleFieldInterface')
        ];
    }
}
