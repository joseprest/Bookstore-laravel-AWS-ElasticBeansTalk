<?php namespace Manivelle\Channels\Books\Fields;

use Panneau\Support\Field;

class PretNumeriqueIds extends Field
{
    protected $attributes = [
        'type' => 'pretnumerique_ids'
    ];
    
    protected $hasMany = \Manivelle\Channels\Books\Fields\PretNumeriqueId::class;
}
