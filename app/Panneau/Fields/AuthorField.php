<?php namespace Manivelle\Panneau\Fields;

use Manivelle;
use Panneau\Fields\MetadataString;

class AuthorField extends MetadataString
{
    
    protected $attributes = [
        'type' => 'author'
    ];
    
    public static function getAuthors($query = [])
    {
        $resource = Manivelle::resource('bubbles');
        return $resource->getFieldValues('metadatas', 'author', $query);
    }
}
