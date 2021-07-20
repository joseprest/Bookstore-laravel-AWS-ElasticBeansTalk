<?php namespace Manivelle\Channels\Books\Fields;

use Panneau\Support\Field;

class BookLibraries extends Field
{
    protected $attributes = [
        'type' => 'book_libraries'
    ];
    
    protected $hasMany = \Manivelle\Channels\Books\Fields\BookLibrary::class;
}
