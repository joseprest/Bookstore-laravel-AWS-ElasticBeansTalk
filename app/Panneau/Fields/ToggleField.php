<?php namespace Manivelle\Panneau\Fields;

use Panneau\Fields\Checkbox;
use Panneau\Fields\MetadataBoolean;

class ToggleField extends MetadataBoolean
{
    protected $attributes = array(
        'type' => 'toggle'
    );
}
