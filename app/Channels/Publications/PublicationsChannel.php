<?php namespace Manivelle\Channels\Publications;

use Manivelle\Support\ChannelType;
use Manivelle;

class PublicationsChannel extends ChannelType
{
    protected $attributes = [
        'type' => 'publications',
        'bubbleType' => 'publication'
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
            // [
            //     'name' => 'publisher',
            //     'label' => trans('channels/publications.filters.publishers'),
            //     'type' => 'values',
            //     'values' => function () {
            //         return $this->getTokens('publisher');
            //     }
            // ],
            // [
            //     'name' => 'author',
            //     'label' => trans('channels/publications.filters.authors'),
            //     'type' => 'values',
            //     'values' => function () {
            //         return $this->getAuthorsOptions();
            //     }
            // ],
            [
                'name' => 'collection',
                'label' => trans('channels/publications.filters.collection'),
                'type' => 'values',
                'values' => function () {
                    return $this->getCollectionsOptions();
                }
            ],
            [
                'name' => 'categories',
                'label' => trans('channels/publications.filters.categories'),
                'type' => 'values',
                'values' => function () {
                    return $this->getCategoriesOptions();
                }
            ]
        ];
    }

    public function getCollectionsOptions($params = [])
    {
        $bubbleType = Manivelle::bubbleType($this->bubbleType);
        return $bubbleType->getCategoriesTokens($params, 'collection');
    }

    public function getCategoriesOptions($params = [])
    {
        $bubbleType = Manivelle::bubbleType($this->bubbleType);
        return $bubbleType->getCategoriesTokens($params);
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
