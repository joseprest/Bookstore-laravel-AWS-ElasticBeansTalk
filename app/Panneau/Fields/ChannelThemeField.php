<?php namespace Manivelle\Panneau\Fields;

use Manivelle;
use Panneau\Support\Field;

class ChannelThemeField extends Field
{
    
    protected $attributes = [
        'type' => 'channel_theme'
    ];
    
    protected $fields = [
        'color_light' => \Manivelle\Panneau\Fields\ColorField::class,
        'color_medium' => \Manivelle\Panneau\Fields\ColorField::class,
        'color_normal' => \Manivelle\Panneau\Fields\ColorField::class,
        'color_dark' => \Manivelle\Panneau\Fields\ColorField::class,
        'color_darker' => \Manivelle\Panneau\Fields\ColorField::class,
        'color_shadow' => \Manivelle\Panneau\Fields\ColorField::class,
        'color_shadow_darker' => \Manivelle\Panneau\Fields\ColorField::class
    ];
}
