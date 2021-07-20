<?php namespace Manivelle\Channels\Events\Fields;

use DB;
use Panneau\Fields\MetadataString;
use Illuminate\Support\Str;

class EventCategory extends MetadataString
{
    protected $attributes = array(
        'type' => 'event_category',
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
