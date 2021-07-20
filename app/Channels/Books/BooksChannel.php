<?php namespace Manivelle\Channels\Books;

use Manivelle;
use Manivelle\Support\ChannelType;

use Manivelle\Panneau\Fields\AuthorField;
use Manivelle\Channels\Books\Fields\BookCategory;
use Manivelle\Channels\Books\Fields\BookLibrary;
use Manivelle\Models\Bubble;

use Illuminate\Support\Str;

class BooksChannel extends ChannelType
{

    protected $attributes = [
        'type' => 'books',
        'bubbleType' => 'book'
    ];

    protected $collections = null;
    protected $authors = null;

    public function settings()
    {
        return [
            [
                'name' => 'library',
                'type' => 'select',
                'label' => trans('channels/books.settings.library'),
                'values' => function () {
                    return $this->getLibrariesOptions();
                }
            ]
        ];
    }

    public function views()
    {
        return [
            [
                'key' => 'circles',
                'label' => trans('channels/books.views.circles'),
                'props' => [
                    'view' => 'channel:main'
                ]
            ]
        ];
    }

    public function filters()
    {
        return [
            [
                'name' => 'collection',
                'label' => trans('channels/books.filters.collection'),
                'field' => 'select',
                'type' => 'circles',
                'values' => function () {
                    return $this->getCategoriesOptions();
                }
            ],
            [
                'name' => 'author',
                'label' => trans('channels/books.filters.author'),
                'field' => 'select',
                'type' => 'list',
                'layout' => 'alphabetic',
                'alphabetic_sort' => 'name',
                'values' => function () {
                    return $this->getAuthorsOptions();
                }
            ]
        ];
    }

    public function getLibrariesOptions()
    {
        $options = [];
        $libraries = config('manivelle.channels.books.libraries');
        foreach ($libraries as $library) {
            $options[] = [
                'value' => $library['key'],
                'label' => $library['title']
            ];
        }

        usort($items, function ($a, $b) {
            return strcmp($a['label'], $b['label']);
        });

        return $options;
    }

    public function getCategoriesOptions()
    {
        $bubbleType = Manivelle::bubbleType($this->bubbleType);
        return $bubbleType->getCategoriesTokens();
    }

    public function getAuthorsOptions($params = [])
    {
        $tokens = $this->getTokens('authors', $params);

        $items = [];
        foreach ($tokens as $token) {
            if (preg_match('/^[0-9]/', $token['value'])) {
                continue;
            }
            $items[] = [
                'label' => $token['label'],
                'value' => $token['value'],
                'alpha' => $token['value']
            ];
        }

        usort($items, function ($a, $b) {
            return strcmp($a['alpha'], $b['alpha']);
        });

        return $items;
    }
}
