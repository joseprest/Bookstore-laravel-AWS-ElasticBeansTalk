<?php namespace Manivelle\Panneau\Fields;

use Panneau\Support\Field;

class PositionField extends Field
{
    protected $attributes = array(
        'type' => 'position'
    );
    
    protected $fields = [
        'latitude' => \Panneau\Fields\MetadataFloat::class,
        'longitude' => \Panneau\Fields\MetadataFloat::class,
        'radius' => \Panneau\Fields\MetadataInteger::class,
    ];
}
