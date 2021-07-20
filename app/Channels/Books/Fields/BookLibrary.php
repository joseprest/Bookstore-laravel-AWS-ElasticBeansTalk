<?php namespace Manivelle\Channels\Books\Fields;

use Panneau\Fields\MetadataString;
use Folklore\EloquentMediatheque\Models\Metadata;

class BookLibrary extends MetadataString
{
    protected $attributes = [
        'type' => 'book_library'
    ];
    
    protected function getValueFromMetadatasRelation($item, $name, $fieldName)
    {
        $library = $item->value;
        return array_first(config('manivelle.channels.books.libraries'), function ($key, $value) use ($library) {
            return $value['key'] === $library;
        });
    }
}
