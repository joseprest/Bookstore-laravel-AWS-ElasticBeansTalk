<?php namespace Manivelle\Panneau\Fields;

use Manivelle;
use Panneau\Support\Field;

class AuthorsField extends Field
{
    
    protected $attributes = [
        'type' => 'authors'
    ];
    
    protected $hasMany = Manivelle\Panneau\Fields\AuthorField::class;
    
    public static function getAuthors($query = [])
    {
        $resource = Manivelle::resource('bubbles');
        return $resource->getFieldValues('metadatas', 'authors', $query);
    }
}
