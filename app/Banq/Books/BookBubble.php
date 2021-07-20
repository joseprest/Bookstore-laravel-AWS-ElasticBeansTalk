<?php namespace Manivelle\Banq\Books;

use Manivelle\Channels\Books\BookBubble as BaseBookBubble;

use Manivelle\Support\Str;

use Manivelle\Models\Bubble as BubbleModel;
use Manivelle\Contracts\Bubbles\Cleanable;
use Manivelle\Models\Fields\Category as CategoryModel;

class BookBubble extends BaseBookBubble implements Cleanable
{
    protected $attributes = [
        'type' => 'banq_book'
    ];

    public function fields()
    {
        $fields = parent::fields();

        //Change date field for a year field
        $newFields = [];
        foreach ($fields as $field) {
            if ($field['name'] === 'date') {
                $newFields[] = [
                    'name' => 'date',
                    'type' => 'date',
                    'label' => 'Année de publication',
                    'format' => '%Y'
                ];
            } else {
                $newFields[] = $field;
            }
        }
        $fields = $newFields;

        //Add new fields
        $fields[] = [
            'name' => 'banq_id',
            'type' => 'string',
            'label' => trans('bubbles/banq_book.fields.banq_id')
        ];

        $fields[] = [
            'name' => 'physical_description',
            'type' => 'string',
            'label' => trans('bubbles/banq_book.fields.physical_description')
        ];

        $fields[] = [
            'name' => 'pages',
            'type' => 'integer',
            'label' => trans('bubbles/banq_book.fields.nb_pages')
        ];

        $fields[] = [
            'name' => 'awards',
            'type' => 'strings',
            'label' => trans('bubbles/banq_book.fields.awards')
        ];

        $fields[] = [
            'name' => 'quebec_creator',
            'type' => 'string',
            'label' => trans('bubbles/banq_book.fields.quebec_creator')
        ];

        $fields[] = [
            'name' => 'collections',
            'type' => 'categories',
            'label' => trans('bubbles/banq_book.fields.collections')
        ];

        $fields[] = [
            'name' => 'nationals_collections',
            'type' => 'categories',
            'label' => trans('bubbles/banq_book.fields.nationals_collections')
        ];

        $fields[] = [
            'name' => 'origin',
            'type' => 'categories',
            'label' => trans('bubbles/banq_book.fields.origin')
        ];

        $fields[] = [
            'name' => 'characters',
            'type' => 'categories',
            'label' => trans('bubbles/banq_book.fields.characters')
        ];

        $fields[] = [
            'name' => 'subjects',
            'type' => 'categories',
            'label' => trans('bubbles/banq_book.fields.subjects')
        ];

        $fields[] = [
            'name' => 'genres',
            'type' => 'categories',
            'label' => trans('bubbles/banq_book.fields.genres')
        ];

        $fields[] = [
            'name' => 'locations',
            'type' => 'categories',
            'label' => trans('bubbles/banq_book.fields.locations')
        ];

        return $fields;
    }

    public function filters()
    {
        return [
            [
                'name' => 'genres',
                'type' => 'tokens',
                'label' => trans('bubbles/banq_book.filters.genres'),
                'multiple' => true,
                'values_cacheable' => false,
                'tokens_cacheable' => false,
                'queryScope' => function ($query, $value) {
                    /*$query->whereHas('metadatas', function ($query) use ($value) {
                        $query->where('mediatheque_metadatas.name', 'LIKE', 'genres');
                        $query->where('mediatheque_metadatas.value', $value);
                    });*/

                    $query->whereHas('fields_categories', function ($query) use ($value) {
                        $query->where('fields_categories_morph_pivot.field_name', 'genres');
                        $query->where('fields_categories.external_id', $value);
                    });
                },
                'value' => function ($bubble, $fields) {
                    return $fields->genres ? $fields->genres->map(function ($item) {
                        return ($item->id);
                    }):null;
                },
                'tokens' => function ($params = []) {
                    return $this->getTokens('genres', $params);
                }
            ],

            [
                'name' => 'subjects',
                'type' => 'tokens',
                'label' => trans('bubbles/banq_book.filters.subjects'),
                'multiple' => true,
                'values_cacheable' => false,
                'tokens_cacheable' => false,
                'queryScope' => function ($query, $value) {
                    /*$query->whereHas('metadatas', function ($query) use ($value) {
                        $query->where('mediatheque_metadatas.name', 'LIKE', 'subjects');
                        $query->where('mediatheque_metadatas.value', $value);
                    });*/

                    $query->whereHas('fields_categories', function ($query) use ($value) {
                        $query->where('fields_categories_morph_pivot.field_name', 'subjects');
                        $query->where('fields_categories.external_id', $value);
                    });
                },
                'value' => function ($bubble, $fields) {
                    return $fields->subjects ? $fields->subjects->map(function ($item) {
                        return ($item->id);
                    }):null;
                },
                'tokens' => function ($params = []) {
                    return $this->getTokens('subjects', $params);
                }
            ],

            [
                'name' => 'characters',
                'type' => 'tokens',
                'label' => trans('bubbles/banq_book.filters.characters'),
                'multiple' => true,
                'values_cacheable' => false,
                'tokens_cacheable' => false,
                'queryScope' => function ($query, $value) {
                    /*$query->whereHas('metadatas', function ($query) use ($value) {
                        $query->where('mediatheque_metadatas.name', 'LIKE', 'characters');
                        $query->where('mediatheque_metadatas.value', $value);
                    });*/

                    $query->whereHas('fields_categories', function ($query) use ($value) {
                        $query->where('fields_categories_morph_pivot.field_name', 'characters');
                        $query->where('fields_categories.external_id', $value);
                    });
                },
                'value' => function ($bubble, $fields) {
                    return $fields->characters ? $fields->characters->map(function ($item) {
                        return ($item->id);
                    }):null;
                },
                'tokens' => function ($params = []) {
                    return $this->getTokens('characters', $params);
                }
            ],

            [
                'name' => 'locations',
                'type' => 'tokens',
                'label' => trans('bubbles/banq_book.filters.locations'),
                'multiple' => true,
                'values_cacheable' => false,
                'tokens_cacheable' => false,
                'queryScope' => function ($query, $value) {
                    /*$query->whereHas('metadatas', function ($query) use ($value) {
                        $query->where('mediatheque_metadatas.name', 'LIKE', 'locations');
                        $query->where('mediatheque_metadatas.value', $value);
                    });*/

                    $query->whereHas('fields_categories', function ($query) use ($value) {
                        $query->where('fields_categories_morph_pivot.field_name', 'LIKE', 'locations');
                        $query->where('fields_categories.external_id', $value);
                    });
                },
                'value' => function ($bubble, $fields) {
                    return $fields->locations ? $fields->locations->map(function ($item) {
                        return ($item->id);
                    }):null;
                },
                'tokens' => function ($params = []) {
                    return $this->getTokens('locations', $params);
                }
            ],

            [
                'name' => 'awards',
                'type' => 'tokens',
                'label' => trans('bubbles/banq_book.filters.awards'),
                'multiple' => true,
                'queryScope' => function ($query, $value) {
                    $query->whereHas('metadatas', function ($query) use ($value) {
                        $query->where('mediatheque_metadatas.name', 'LIKE', 'awards');
                        $query->where('mediatheque_metadatas.value', $value);
                    });
                },
                'value' => function ($bubble, $fields) {
                    return $fields->awards ? $fields->awards->map(function ($item) {
                        return preg_replace('/\,\s*[0-9\-]+$/', '', $item);
                    }):null;
                },
                'tokens' => function ($params = []) {
                    $awards = $this->getTokens('awards', $params);
                    $tokens = [];
                    foreach ($awards as $award) {
                        $label = preg_replace('/\,\s*[0-9\-]+$/', '', $award['label']);
                        $tokens[$label] = [
                            'label' => $label,
                            'value' => $label
                        ];
                    }
                    return array_values($tokens);
                }
            ]

        ];
    }

    public function snippet()
    {
        $snippet = parent::snippet();

        return array_merge($snippet, [
            'title' => function ($fields) {
                return $fields->title ? $fields->title:'';
            },
            'picture' => function ($fields) {
                return $fields->cover_front ? $fields->cover_front:null;
            },
            'description' => function ($fields) {
                $description = [];
                if (!empty($fields->physical_description)) {
                    $description[] = $fields->physical_description;
                }
                if (!empty($fields->collections)) {
                    foreach ($fields->collections as $collection) {
                        $description[] = $collection->name;
                    }
                }
                if (!empty($fields->awards)) {
                    foreach ($fields->awards as $award) {
                        $description[] = $award;
                    }
                }
                return implode("\n", $description);
            },
            'summary' => function ($fields) {
                return '';
            },
            'link' => function ($fields) {
                if (!empty($fields->banq_id)) {
                    return 'http://www.banq.qc.ca/ressources_en_ligne/romansalire/notice.html?id='.$fields->banq_id;
                }

                return $fields->link;
            }
        ]);
    }

    public function shouldCleanBubble(BubbleModel $bubble)
    {
        $fields = $bubble->fields;
        if (!$fields->cover_front || empty($fields->cover_front->width)) {
            return true;
        } elseif ($bubble->pictures->isEmpty()) {
            return true;
        } elseif (sizeof($bubble->pictures) === 1) {
            $picture = $bubble->pictures[0];
            if ($picture->width === 1 && $picture->height === 1 && $picture->mime === 'image/gif') {
                return true;
            }
        }
        return false;
    }

    /**
     * Email
     */
    public function getEmailLayout()
    {
        return 'normal';
    }

    public function getEmailFields()
    {
        return [
            'author',
            'publisher',
            'awards',
            'characters',
            'categories',
            'subjects',
            'genres',
            'date'
        ];
    }

    public function getEmailTopButton($url)
    {
        return [
            'label' => trans('share.bubbles.banq_book.see_availability'),
            'url' => $url
        ];
    }

    public function getEmailBottomButton($url)
    {
        return [
            'label' => trans('share.bubbles.banq_book.see_more'),
            'url' => trans('share.bubbles.banq_book.see_more_url')
        ];
    }
}
