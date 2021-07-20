<?php namespace Manivelle\Panneau\Fields;

use Manivelle;
use Panneau\Support\Field;

class FiltersField extends Field
{
    
    protected $attributes = [
        'type' => 'filters'
    ];
    
    protected $hasMany = \Manivelle\Panneau\Fields\FilterField::class;
}
