<?php namespace Manivelle\Panneau\Fields;

use Panneau\Support\Field;

class ScreenResolutionField extends Field
{
    protected $attributes = array(
        'type' => 'screen_resolution'
    );
    
    protected $fields = [
        'x' => \Panneau\Fields\MetadataInteger::class,
        'y' => \Panneau\Fields\MetadataInteger::class
    ];
}
