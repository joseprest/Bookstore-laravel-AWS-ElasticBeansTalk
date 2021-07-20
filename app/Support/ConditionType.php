<?php namespace Manivelle\Support;

use Manivelle;

use Panneau\Support\ModelFields;

use GraphQL\Type\Definition\Type;

use Carbon\Carbon;

class ConditionType extends ModelFields
{
    protected $defaultAttributes = array(
        'type' => 'condition'
    );
    
    public function fields()
    {
        return [
            [
                'name' => 'days',
                'type' => 'condition_weekdays',
                'graphql_type' => Type::listOf(Type::string())
            ],
            [
                'name' => 'daterange',
                'type' => 'condition_daterange',
                'graphql_type' => Type::listOf(Type::string())
            ],
            [
                'name' => 'date',
                'type' => 'condition_date'
            ],
            [
                'name' => 'time',
                'type' => 'condition_time',
                'graphql_type' => Type::listOf(Type::string())
            ]
        ];
    }
    
    public function snippet()
    {
        return [
            'title' => function ($fields, $item) {
                return $item->name;
            },
            'subtitle' => function ($fields, $item) {
                return null;
            },
            'description' => function ($fields, $item) {
                return $item->description;
            }
        ];
    }
}
