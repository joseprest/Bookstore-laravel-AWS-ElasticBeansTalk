<?php namespace Manivelle\Channels\Books;

use Manivelle\Support\BubbleType;
use Panneau;
use Manivelle\Models\Bubble;
use Carbon\Carbon;
use Manivelle\Contracts\Bubbles\Cleanable;
use Illuminate\Support\Str;
use DB;
use Log;

class BookBubble extends BubbleType implements Cleanable
{
    protected $attributes = [
        'type' => 'book'
    ];

    public function attributes()
    {
        return [
            'label' => trans('bubbles/book.label')
        ];
    }

    public function fields()
    {
        $fields = parent::fields();
        return array_merge($fields, [
            [
                'name' => 'title',
                'type' => 'text',
                'label' => trans('bubbles/book.fields.title')
            ],
            /*[
                'name' => 'author',
                'type' => 'author',
                'label' => trans('bubbles/book.fields.author')
            ],*/
            [
                'name' => 'authors',
                'type' => 'persons',
                'label' => trans('bubbles/book.fields.authors')
            ],
            [
                'name' => 'pretnumerique_ids',
                'type' => 'pretnumerique_ids',
                'label' => trans('bubbles/book.fields.pretnumerique_ids')
            ],
            [
                'name' => 'pretnumerique_id',
                'type' => 'string',
                'label' => trans('bubbles/book.fields.pretnumerique_id')
            ],
            [
                'name' => 'isbn',
                'type' => 'string',
                'label' => trans('bubbles/book.fields.isbn')
            ],
            [
                'name' => 'publisher',
                'type' => 'string',
                'label' => trans('bubbles/book.fields.publisher')
            ],
            [
                'name' => 'language',
                'type' => 'string',
                'label' => trans('bubbles/book.fields.language')
            ],
            [
                'name' => 'date',
                'type' => 'date',
                'label' => trans('bubbles/book.fields.date'),
                'format' => '%Y'
            ],
            [
                'name' => 'summary',
                'type' => 'text',
                'label' => trans('bubbles/book.fields.summary')
            ],
            [
                'name' => 'cover_front',
                'type' => 'picture',
                'label' => trans('bubbles/book.fields.cover_front')
            ],
            [
                'name' => 'cover_back',
                'type' => 'picture',
                'label' => trans('bubbles/book.fields.cover_back')
            ],
            [
                'name' => 'link',
                'type' => 'link',
                'label' => trans('bubbles/book.fields.link')
            ],
            /*[
                'name' => 'category',
                'type' => 'book_category',
                'label' => trans('bubbles/book.fields.category')
            ],*/
            [
                'name' => 'categories',
                'type' => 'categories',
                'label' => trans('bubbles/book.fields.categories')
            ],
            [
                'name' => 'libraries',
                'type' => 'book_libraries',
                'graphql_type' => 'text',
                'label' => trans('bubbles/book.fields.libraries')
            ]
        ]);
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
                return $fields->link;
            }
        ]);
    }

    public function filters()
    {
        return [
            [
                'name' => 'author',
                'type' => 'tokens',
                'label' => trans('bubbles/book.filters.author'),
                'multiple' => true,
                /*'queryScopeMetadata' => function ($query, $value) {
                    $query->whereIn('mediatheque_metadatas.name', ['authors[id]', 'author']);
                    $query->where('mediatheque_metadatas.value', $value);
                },*/
                'queryScope' => function ($query, $value) {
                    /*$query->whereHas('metadatas', function ($query) use ($value) {
                        $query->whereIn('mediatheque_metadatas.name', ['authors[id]', 'author']);
                        $query->where('mediatheque_metadatas.value', $value);
                    });*/

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
                'label' => trans('bubbles/book.filters.publisher'),
                'multiple' => true,
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
            /*[
                'name' => 'date',
                'type' => 'date',
                'label' => trans('bubbles/book.filters.date'),
                'queryScopeMetadata' => function ($query, $value) {
                    $startDate = null;
                    $endDate = null;
                    $future = false;
                    $dates = !is_array($value) ? explode(',', $value):$value;
                    switch (array_get($dates, '0')) {
                        case 'future':
                            $startDate = Carbon::now()->setTime(0, 0, 0)->toDateTimeString();
                            $future = true;
                            break;
                        default:
                            if (isset($dates[0]) && !empty($dates[0])) {
                                $startDate = Carbon::parse($dates[0])->toDateTimeString();
                            }
                            if (isset($dates[1]) && !empty($dates[1])) {
                                $endDate = Carbon::parse($dates[1])->setTime(23, 59, 59)->toDateTimeString();
                            } elseif (isset($dates[0])) {
                                $endDate = Carbon::parse($dates[0])->setTime(23, 59, 59)->toDateTimeString();
                            }
                            break;
                    }
                    if ($startDate && $endDate) {
                        $query->where('mediatheque_metadatas.name', 'date');
                        $query->where('mediatheque_metadatas.value_date', '>=', $startDate);
                        $query->where('mediatheque_metadatas.value_date', '<=', $endDate);
                    } elseif ($startDate) {
                        $query->where('mediatheque_metadatas.name', 'date');
                        $query->where('mediatheque_metadatas.value_date', $future ? '>=':'=', $startDate);
                    }
                },
                'value' => function ($bubble, $fields) {
                    return $fields->date;
                }
            ],*/
            [
                'name' => 'collection',
                'type' => 'select',
                'label' => trans('bubbles/book.filters.collection'),
                'values_cacheable' => true,
                'tokens_cacheable' => true,
                'multiple' => true,
                /*'queryScopeMetadata' => function ($query, $value) {
                    $query->where('mediatheque_metadatas.name', 'categories[id]');
                    if (is_array($value)) {
                        $query->wherein('mediatheque_metadatas.value', $value);
                    } else {
                        $query->where('mediatheque_metadatas.value', $value);
                    }
                },*/
                'queryScope' => function ($query, $value) {
                    /*$query->whereIn('id', function ($query) use ($value) {
                        $query->select('mtb_collection.metadatable_id')
                                ->from('mediatheque_metadatables as mtb_collection')
                                ->join(
                                    'mediatheque_metadatas as mtd_collection',
                                    'mtb_collection.metadata_id',
                                    '=',
                                    'mtd_collection.id'
                                )
                                ->where('mtb_collection.metadatable_type', Bubble::class)
                                ->where('mtd_collection.name', 'categories[id]')
                                ->where('mtd_collection.value', $value);
                    });*/

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
            ],
            [
                'name' => 'library',
                'type' => 'select',
                'label' => trans('bubbles/book.filters.library'),
                'values_cacheable' => true,
                'tokens_cacheable' => true,
                'queryScopeMetadata' => function ($query, $value) {
                    $query->where('mediatheque_metadatas.name', 'libraries')
                            ->where('mediatheque_metadatas.value', $value);
                },
                'queryScope' => function ($query, $value) {
                    $query->whereIn('id', function ($query) use ($value) {
                        $query->select('mtb_library.metadatable_id')
                                ->from('mediatheque_metadatables as mtb_library')
                                ->join('mediatheque_metadatas as mtd_library', 'mtb_library.metadata_id', '=', 'mtd_library.id')
                                ->where('mtb_library.metadatable_type', Bubble::class)
                                ->where('mtd_library.name', 'libraries')
                                ->where('mtd_library.value', $value);
                    });

                    /*$query->whereHas('metadatas', function($query) use ($value)
                    {
                        $query->where('mediatheque_metadatas.name', 'libraries');
                        $query->where('mediatheque_metadatas.value', $value);
                    });*/
                },
                'value' => function ($bubble, $fields) {
                    return $fields->library;
                },
                'tokens' => function ($params = []) {
                    return $this->getLibrariesTokens($params);
                },
                'values' => function ($params = []) {
                    return $this->getLibrariesTokens($params);
                }
            ]
        ];
    }

    public function getCategoriesTokens($params = [])
    {
        $tokens = $this->getTokens('categories', $params);

        $items = [];
        foreach ($tokens as $token) {
            $id = (string)array_get($token, 'value');
            if (preg_match('/^FB[A-Z0-9]+/', $id)) {
                continue;
            }
            $items[] = [
                'label' => array_get($token, 'label'),
                'value' => array_get($token, 'value')
            ];
        }

        usort($items, function ($a, $b) {
            return strcmp($a['label'], $b['label']);
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

    public function getLibrariesTokens($params = [])
    {
        $tokens = [];
        $allLibraries = config('manivelle.channels.books.libraries');
        $libraries = $this->getTokens('libraries', $params);

        foreach ($libraries as $library) {
            $libraryKey = array_get($library, 'value');
            $library = array_first($allLibraries, function ($key, $library) use ($libraryKey) {
                return $library['key'] === $libraryKey;
            });

            if ($library) {
                $tokens[] = [
                    'value' => $library['key'],
                    'label' => $library['title']
                ];
            }
        }

        return $tokens;
    }

    public function getLibrariesOptions()
    {
        $options = [
            [
                'value' => '',
                'label' => trans('bubbles/book.libraries_options')
            ]
        ];
        $libraries = config('manivelle.channels.books.libraries');
        foreach ($libraries as $library) {
            $options[] = [
                'value' => $library['key'],
                'label' => $library['title']
            ];
        }

        return $options;
    }

    public function shouldCleanBubble(Bubble $bubble)
    {
        $handle = $bubble->handle;
        $bubble->load('source');

        //Check if there is a pretnumerique id
        if ($bubble->source &&
            $bubble->source->handle === 'pretnumerique' &&
            (
                !isset($bubble->fields->pretnumerique_id) ||
                empty($bubble->fields->pretnumerique_id) ||
                $handle !== 'pretnumerique_'.Str::slug($bubble->fields->pretnumerique_id)
            )
        ) {
            Log::info('Bubble #'.$bubble->id.' has no Pretnumerique ID. '.json_encode([
                'handle' => $handle,
                'pretnumerique_id' => $bubble->fields->pretnumerique_id,
                'isbn' => $bubble->fields->isbn
            ]));
            return true;
        }

        //Check if the book is in excluded categories
        if ($bubble->fields->categories && sizeof($bubble->fields->categories)) {
            $excludedCategories = config('manivelle.sources.pretnumerique.excluded_categories');
            foreach ($bubble->fields->categories as $category) {
                if (!empty($category->id) && in_array($category->id, $excludedCategories)) {
                    Log::info('Bubble #'.$bubble->id.' has excluded category. '.json_encode([
                        'handle' => $handle,
                        'pretnumerique_id' => $bubble->fields->pretnumerique_id,
                        'isbn' => $bubble->fields->isbn,
                        'categories' => $bubble->fields->categories
                    ]));
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Email
     */
    public function getEmailFields()
    {
        return [
            'author',
            'publisher',
            'isbn',
            'categories',
            'language',
            'date'
        ];
    }

    public function getEmailTopButton($url)
    {
        return [
            'label' => trans('share.bubbles.book.borrow'),
            'url' => $url
        ];
    }

    public function getEmailBottomButton($url)
    {
        return [
            'label' => trans('share.bubbles.book.see_more'),
            'url' => trans('share.bubbles.book.see_more_url')
        ];
    }
}
