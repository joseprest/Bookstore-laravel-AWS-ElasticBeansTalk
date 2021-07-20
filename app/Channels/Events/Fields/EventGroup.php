<?php namespace Manivelle\Channels\Events\Fields;

use Panneau\Support\Field;
use DB;
use Illuminate\Support\Str;

class EventGroup extends Field
{
    protected $attributes = array(
        'type' => 'event_group',
        'tokenFields' => [
            'value' => 'id',
            'label' => 'name'
        ],
        'tokenSearchFields' => [
            'name'
        ]
    );
    
    protected $fields = [
        'id' => \Panneau\Fields\MetadataString::class,
        'name' => \Panneau\Fields\MetadataString::class
    ];
}
