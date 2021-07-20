<?php namespace Manivelle\Panneau\Fields;

use Manivelle;
use Panneau\Support\Field;

class StringsField extends Field
{
    
    protected $attributes = [
        'type' => 'texts'
    ];
    
    protected $hasMany = Manivelle\Panneau\Fields\StringField::class;
}
