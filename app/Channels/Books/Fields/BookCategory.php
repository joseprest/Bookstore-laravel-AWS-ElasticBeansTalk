<?php namespace Manivelle\Channels\Books\Fields;

use Manivelle;
use Panneau\Fields\MetadataString;

class BookCategory extends MetadataString
{
    protected $attributes = [
        'type' => 'book_category'
    ];
    
    protected function getValueFromMetadatasRelation($item, $name, $fieldName)
    {
        $category = $item->value;
        return $category;
        /*return array_first(trans('books.categories'), function($key, $value) use ($category)
        {
            return $key === $category;
        });*/
    }
}
