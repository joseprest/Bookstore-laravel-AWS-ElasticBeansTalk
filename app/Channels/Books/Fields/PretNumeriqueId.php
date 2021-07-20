<?php namespace Manivelle\Channels\Books\Fields;

use Panneau\Support\Field;
use Panneau\Fields\MetadataString;
use Folklore\EloquentMediatheque\Models\Metadata;

class PretNumeriqueId extends Field
{
    protected $attributes = [
        'type' => 'pretnumerique_id'
    ];
    
    protected $fields = [
        'id' => \Panneau\Fields\MetadataString::class,
        'library' => \Panneau\Fields\MetadataString::class
    ];
}
