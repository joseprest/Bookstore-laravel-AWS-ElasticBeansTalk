<?php namespace Manivelle\Channels\Events\Fields;

use Panneau\Support\Field;

class EventCategories extends Field
{
    protected $attributes = array(
        'type' => 'event_categories'
    );
    
    protected $hasMany = \Manivelle\Channels\Events\Fields\EventCategory::class;
}
