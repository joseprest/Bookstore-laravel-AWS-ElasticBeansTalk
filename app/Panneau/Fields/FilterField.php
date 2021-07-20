<?php namespace Manivelle\Panneau\Fields;

use Manivelle;
use Panneau\Support\Field;

class FilterField extends Field
{
    
    protected $attributes = [
        'type' => 'filter'
    ];
    
    protected $fields = [
        'name' => \Panneau\Fields\MetadataString::class,
        'value' => \Manivelle\Panneau\Fields\FilterValueField::class
    ];
}
