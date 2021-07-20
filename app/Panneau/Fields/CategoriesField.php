<?php namespace Manivelle\Panneau\Fields;

use Manivelle;
use Panneau\Support\Field;

class CategoriesField extends Field
{
    
    protected $attributes = [
        'type' => 'categories'
    ];
    
    protected $hasMany = \Manivelle\Panneau\Fields\CategoryField::class;
}
