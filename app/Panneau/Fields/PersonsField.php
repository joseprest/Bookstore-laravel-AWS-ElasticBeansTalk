<?php namespace Manivelle\Panneau\Fields;

use Manivelle;
use Panneau\Support\Field;

class PersonsField extends Field
{
    
    protected $attributes = [
        'type' => 'persons',
        'tokenFields' => [
            'value' => 'id',
            'label' => 'name'
        ],
        'tokenSearchFields' => [
            'name'
        ]
    ];
    
    protected $hasMany = \Manivelle\Panneau\Fields\PersonField::class;
}
