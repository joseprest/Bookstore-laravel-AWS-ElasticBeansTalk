<?php namespace Manivelle\Panneau\Fields;

use Panneau\Support\Field;

class ScreenTechnicalField extends Field
{
    protected $attributes = array(
        'type' => 'screen_technical'
    );
    
    protected $fields = [
        'vendor' => \Panneau\Fields\MetadataString::class,
        'model' => \Panneau\Fields\MetadataString::class,
        'serial_number' => \Panneau\Fields\MetadataString::class,
        'size' => \Panneau\Fields\MetadataString::class,
        'resolution' => \Manivelle\Panneau\Fields\ScreenResolutionField::class,
    ];
}
