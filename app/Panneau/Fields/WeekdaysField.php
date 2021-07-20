<?php namespace Manivelle\Panneau\Fields;

use Manivelle;
use Panneau\Support\Field;

class WeekdaysField extends Field
{
    
    protected $attributes = [
        'type' => 'weekdays'
    ];
    
    protected $hasMany = \Manivelle\Panneau\Fields\WeekdayField::class;
}
