<?php namespace Manivelle\Channels\Publications;

use Manivelle\Support\BubbleType;
use Manivelle\Models\Bubble;
use Manivelle\Contracts\Bubbles\Cleanable;

class PublicationBubble extends BubbleType implements Cleanable
{
    protected $attributes = [
        'type' => 'publication'
    ];

    public function attributes()
    {
        return [
            'label' => trans('bubbles/publication.label')
        ];
    }

    public function fields()
    {
        $fields = parent::fields();
        return array_merge([
            [
                'name' => 'title',
                'type' => 'text',
                'label' => trans('bubbles/publication.fields.title')
            ],
            [
                'name' => 'authors',
                'type' => 'persons',
                'label' => trans('bubbles/publication.fields.authors')
            ],
            [
                'name' => 'isbn',
                'type' => 'string',
                'label' => trans('bubbles/publication.fields.isbn')
            ],
            [
                'name' => 'publisher',
                'type' => 'string',
                'label' => trans('bubbles/publication.fields.publisher')
            ],
            [
                'name' => 'language',
                'type' => 'string',
                'label' => trans('bubbles/publication.fields.language')
            ],
            [
                'name' => 'date',
                'type' => 'date',
                'label' => trans('bubbles/publication.fields.date'),
                'format' => '%Y'
            ],
            [
                'name' => 'summary',
                'type' => 'text',
                'label' => trans('bubbles/publication.fields.summary')
            ],
            [
                'name' => 'cover_front',
                'type' => 'picture',
                'label' => trans('bubbles/publication.fields.cover_front')
            ],
            [
                'name' => 'cover_back',
                'type' => 'picture',
                'label' => trans('bubbles/publication.fields.cover_back')
            ],
            [
                'name' => 'link',
                'type' => 'link',
                'label' => trans('bubbles/publication.fields.link')
            ],
            [
                'name' => 'collection',
                'type' => 'category',
                'label' => trans('bubbles/publication.fields.collection')
            ],
            [
                'name' => 'categories',
                'type' => 'categories',
                'label' => trans('bubbles/publication.fields.categories')
            ],
        ], $fields);
    }

    public function suggestions()
    {
        return [
            'author',
            'publisher',
            'collection'
        ];
    }

    public function snippet()
    {
        $snippet = parent::snippet();

        return array_merge($snippet, [
            'title' => function ($fields) {
                return $fields->title ? $fields->title:'';
            },
            'subtitle' => function ($fields) {
                $authors = [];
                if (isset($fields->authors)) {
                    foreach ($fields->authors as $author) {
                        if (!empty($author->name)) {
                            $authors[] = $author->name;
                        }
                    }
                }
                return implode(', ', $authors);
            },
            'picture' => function ($fields, $model) {
                $picture = null;
                if ($fields->cover_front) {
                    $picture = $fields->cover_front;
                } elseif (!$model->pictures->isEmpty()) {
                    $picture = $model->pictures[0];
                }
                return $picture;
            },
            'description' => 'summary',
            'summary' => function ($fields) {
                return mb_substr($fields->summary, 0, 255, 'utf-8').'...';
            },
            'link' => function ($fields) {
                $url = $fields->link;
                if (!empty($url)) {
                    $url = preg_replace('/^(https?\:\/\/)?(www\.)?cairn\.info/i', 'https://www-cairn-info.scd-proxy.univ-brest.fr', $url);
                }
                return $url;
            }
        ]);
    }

    public function filters()
    {
        return [
            [
                'name' => 'author',
                'type' => 'tokens',
                'label' => trans('bubbles/publication.filters.author'),
                'multiple' => true,
                'queryScope' => function ($query, $value) {
                    $query->whereHas('fields_persons', function ($query) use ($value) {
                        $query->where('fields_persons_morph_pivot.field_name', 'authors');
                        $query->where('fields_persons.external_id', $value);
                    });
                },
                'value' => function ($bubble, $fields) {
                    $authors = isset($fields->authors) ? array_pluck($fields->authors, 'id'):[];
                    return sizeof($authors) ? $authors:[];
                },
                'tokens' => function ($params = []) {
                    return $this->getAuthorsTokens($params);
                }
            ],
            [
                'name' => 'publisher',
                'type' => 'tokens',
                'label' => trans('bubbles/publication.filters.publisher'),
                'queryScopeMetadata' => function ($query, $value) {
                    $query->where('mediatheque_metadatas.name', 'publisher');
                    $query->where('mediatheque_metadatas.value', $value);
                },
                'queryScope' => function ($query, $value) {
                    $query->whereHas('metadatas', function ($query) use ($value) {
                        $query->where('mediatheque_metadatas.name', 'publisher');
                        $query->where('mediatheque_metadatas.value', $value);
                    });
                },
                'value' => function ($bubble, $fields) {
                    return $fields->publisher;
                },
                'tokens' => function ($params = []) {
                    return $this->getTokens('publisher', $params);
                }
            ],
            [
                'name' => 'collection',
                'type' => 'select',
                'label' => trans('bubbles/publication.filters.collection'),
                'multiple' => true,
                'queryScope' => function ($query, $value) {
                    $query->whereHas('fields_categories', function ($query) use ($value) {
                        $query->where('fields_categories_morph_pivot.field_name', 'collection');
                        $query->where('fields_categories.external_id', $value);
                    });
                },
                'value' => function ($bubble, $fields) {
                    $collections = isset($fields->collection) ? [$fields->collection->id]:[];
                    return sizeof($collections) ? $collections:[];
                },
                'tokens' => function ($params = []) {
                    return $this->getCategoriesTokens($params, 'collection');
                }
            ],
            [
                'name' => 'categories',
                'type' => 'select',
                'label' => trans('bubbles/publication.filters.categories'),
                'multiple' => true,
                'queryScope' => function ($query, $value) {
                    $query->whereHas('fields_categories', function ($query) use ($value) {
                        $query->where('fields_categories_morph_pivot.field_name', 'categories');
                        $query->where('fields_categories.external_id', $value);
                    });
                },
                'value' => function ($bubble, $fields) {
                    $categories = isset($fields->categories) ? array_pluck($fields->categories, 'id'):[];
                    return sizeof($categories) ? $categories:[];
                },
                'tokens' => function ($params = []) {
                    return $this->getCategoriesTokens($params);
                }
            ]
        ];
    }

    public function getCategoriesTokens($params = [], $name = 'categories')
    {
        $tokens = $this->getTokens($name, $params);

        $items = [];
        foreach ($tokens as $token) {
            $items[] = [
                'label' => array_get($token, 'label'),
                'value' => array_get($token, 'value')
            ];
        }

        usort($items, function ($a, $b) {
            return strcmp(str_slug($a['label']), str_slug($b['label']));
        });

        return $items;
    }

    public function getAuthorsTokens($params = [])
    {
        $tokens = $this->getTokens('authors', $params);

        $items = [];
        foreach ($tokens as $token) {
            $items[] = [
                'label' => array_get($token, 'label', array_get($token, 'name')),
                'value' => array_get($token, 'value', array_get($token, 'id'))
            ];
        }

        usort($items, function ($a, $b) {
            return strcmp($a['value'], $b['value']);
        });

        return $items;
    }

    public function shouldCleanBubble(Bubble $bubble)
    {
        $fields = $bubble->fields;
        if (!$fields->cover_front || empty($fields->cover_front->width)) {
            return true;
        } elseif ($bubble->pictures->isEmpty()) {
            return true;
        }
        return false;
    }

    /**
     * Email
     */
    public function getEmailFields()
    {
        return [
            'authors',
            'publisher',
            'collection',
            'categories',
            'date'
        ];
    }

    public function getEmailTopButton($url)
    {
        return [
            'label' => trans('share.bubbles.publication.see_entry'),
            'url' => $url
        ];
    }

    public function getEmailBottomButton($url)
    {
        return [
            'label' => trans('share.bubbles.publication.see_more'),
            'url' => trans('share.bubbles.publication.see_more_url')
        ];
    }

    public function getEmailTexts()
    {
        $texts = parent::getEmailTexts();
        return array_merge($texts, [
            'footer' => trans('share.bubbles.publication.footer'),
        ]);
    }
}
