<?php namespace Manivelle\Banq\Books;

use Manivelle\Panneau\Fields\StringsField;
use Manivelle\Support\ChannelType;
use Manivelle\Support\Str;

use Manivelle\Models\Bubble as BubbleModel;
use Manivelle\Models\Fields\Category as CategoryModel;

class BooksChannel extends ChannelType
{

    protected $attributes = [
        'type' => 'banq_books',
        'bubbleType' => 'banq_book'
    ];
    
    protected $collections = null;
    protected $authors = null;

    public function settings()
    {
        return [];
    }
    
    public function filters()
    {
        
        return [
            [
                'name' => 'genres',
                'label' => trans('channels/banq_books.filters.genres'),
                'type' => 'values',
                'values' => function () {
                    return $this->getValues('genres');
                }
            ],
            [
                'name' => 'subjects',
                'label' => trans('channels/banq_books.filters.subjects'),
                'type' => 'values',
                'values' => function () {
                    return $this->getValues('subjects');
                }
            ],
            [
                'name' => 'characters',
                'label' => trans('channels/banq_books.filters.characters'),
                'type' => 'values',
                'values' => function () {
                    return $this->getValues('characters');
                }
            ],
            [
                'name' => 'locations',
                'label' => trans('channels/banq_books.filters.locations'),
                'type' => 'values',
                'values' => function () {
                    return $this->getValues('locations');
                }
            ],
            [
                'name' => 'awards',
                'label' => trans('channels/banq_books.filters.awards'),
                'type' => 'values',
                'values' => function () {
                    $awards = $this->getValues('awards');
                    $values = [];
                    foreach ($awards as $award) {
                        $label = preg_replace('/\,\s*[0-9\-]+$/', '', $award);
                        $values[$label] = $label;
                    }
                    return array_values($values);
                }
            ],
        ];
    }
}
