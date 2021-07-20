<?php namespace Manivelle\GraphQL\Type\BubbleField;

use GraphQL;
use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\Type as GraphQLType;

use Manivelle\Support\Str;
use Carbon\Carbon;

class BubbleDateFieldType extends GraphQLType
{
    protected $attributes = [
        'name' => 'BubbleDateField',
        'description' => 'A bubble field'
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
                'description' => 'The value of field',
                'resolve' => function ($root) {
                    if (empty($root['value']) || $root['value'] === '0000-00-00' || !strtotime($root['value'])) {
                        return null;
                    }
                    $format = isset($root['field']->format) ? $root['field']->format:'%e %B %Y';
                    return Str::formatDate($root['value'], $format);
                }
            ],
            'date' => [
                'type' => Type::string(),
                'description' => 'The start date',
                'resolve' => function ($root) {
                    if (empty($root['value']) || $root['value'] === '0000-00-00' || !strtotime($root['value'])) {
                        return null;
                    }
                    return Carbon::parse($root['value'])->toDateTimeString();
                }
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
