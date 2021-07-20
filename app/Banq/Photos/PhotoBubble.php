<?php namespace Manivelle\Banq\Photos;

use Manivelle\Support\BubbleType;
use Manivelle\Contracts\Bubbles\Cleanable;
use Manivelle\Models\Bubble;

class PhotoBubble extends BubbleType implements Cleanable
{
    protected $attributes = [
        'type' => 'banq_photo'
    ];

    public function attributes()
    {
        return [
            'label' => trans('bubbles/banq_photo.label')
        ];
    }

    public function fields()
    {
        return [
            [
                'name' => 'banq_id',
                'type' => 'string',
                'label' => trans('bubbles/banq_photo.fields.banq_id')
            ],
            [
                'name' => 'title',
                'type' => 'string',
                'label' => trans('bubbles/banq_photo.fields.title')
            ],
            [
                'name' => 'description',
                'type' => 'string',
                'label' => trans('bubbles/banq_photo.fields.description')
            ],
            [
                'name' => 'authors',
                'type' => 'persons',
                'label' => trans('bubbles/banq_photo.fields.authors')
            ],
            [
                'name' => 'publisher',
                'type' => 'string',
                'label' => trans('bubbles/banq_photo.fields.publisher')
            ],
            [
                'name' => 'link',
                'type' => 'string',
                'label' => trans('bubbles/banq_photo.fields.link')
            ],
            [
                'name' => 'image',
                'type' => 'picture',
                'label' => trans('bubbles/banq_photo.fields.image')
            ],
            [
                'name' => 'date',
                'type' => 'date',
                'label' => trans('bubbles/banq_photo.fields.date'),
                'format' => '%Y'
            ],
            [
                'name' => 'date_text',
                'type' => 'string',
                'label' => trans('bubbles/banq_photo.fields.date_text')
            ],

            [
                'name' => 'physical_description',
                'type' => 'string',
                'label' => trans('bubbles/banq_photo.fields.physical_description')
            ],

            [
                'name' => 'collections',
                'type' => 'categories',
                'label' => trans('bubbles/banq_photo.fields.collections')
            ],

            [
                'name' => 'subjects',
                'type' => 'categories',
                'label' => trans('bubbles/banq_photo.fields.subjects')
            ],

            [
                'name' => 'location',
                'type' => 'location',
                'label' => trans('bubbles/banq_photo.fields.location')
            ]
        ];
    }

    public function snippet()
    {
        $snippet = parent::snippet();

        return array_merge($snippet, [
            'title' => 'title',
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
            'picture' => function ($fields) {
                return $fields->image ? $fields->image:null;
            },
            'description' => function ($fields) {
                $description = [];
                if (!empty($fields->description)) {
                    $description[] = $fields->description;
                } else {
                    if (!empty($fields->date_text)) {
                        $description[] = $fields->date_text;
                    }
                    if (!empty($fields->physical_description)) {
                        $description[] = $fields->physical_description;
                    }
                    if (!empty($fields->collections)) {
                        foreach ($fields->collections as $collection) {
                            $description[] = $collection->name;
                        }
                    }
                }
                return implode("\n", $description);
            },
            'summary' => function ($fields) {
                return '';
            },
            'link' => function ($fields) {
                if (isset($fields->link) && !empty($fields->link)) {
                    return $fields->link;
                }
                return 'http://www.banq.qc.ca/collections/images/notice.html?id='.$fields->banq_id;
            }
        ]);
    }

    public function filters()
    {
        return [
            [
                'name' => 'year',
                'type' => 'year',
                'label' => trans('bubbles/banq_photo.filters.year'),
                'queryScope' => function ($query, $value) {
                    $startDate = $value.'-01-01 00:00:00';
                    $endDate = ((int)$value+1).'-01-01 00:00:00';
                    $query->whereHas('metadatas', function ($query) use ($startDate, $endDate) {
                        $query->where('mediatheque_metadatables.metadatable_position', 'date');
                        $query->where('mediatheque_metadatas.value_date', '>=', $startDate);
                        $query->where('mediatheque_metadatas.value_date', '<', $endDate);
                    });
                },
                'value' => function ($bubble, $fields) {
                    $date = $fields->date;
                    if ($date === '0000-00-00') {
                        return null;
                    }
                    $time = strtotime($date);
                    $year = $time !== 0 ? (int)date('Y', $time):null;
                    return $year;
                }
            ],

            [
                'name' => 'subjects',
                'type' => 'tokens',
                'label' => trans('bubbles/banq_photo.filters.subjects'),
                'multiple' => true,
                'values_cacheable' => false,
                'tokens_cacheable' => false,
                'queryScope' => function ($query, $value) {
                    /*$query->whereHas('metadatas', function ($query) use ($value) {
                        $query->where('mediatheque_metadatables.metadatable_position', 'subjects[%');
                        $query->where('mediatheque_metadatas.value', $value);
                    });*/

                    $query->whereHas('fields_categories', function ($query) use ($value) {
                        $query->where('fields_categories_morph_pivot.field_name', 'LIKE', 'subjects');
                        $query->where('fields_categories.external_id', $value);
                    });
                },
                'value' => function ($bubble, $fields) {
                    return $fields->subjects ? $fields->subjects->map(function ($item) {
                        return $item->id;
                    }):[];
                },
                'tokens' => function ($params = []) {
                    return $this->getTokens('subjects', $params);
                }
            ]
        ];
    }

    public static function normalizeLocation($location)
    {
        $location = preg_replace('/(\, P\.Q\.|\, Que)$/i', '', $location);
        $location = preg_replace('/\?$/i', '', $location);

        return trim($location);
    }

    public function shouldCleanBubble(Bubble $bubble)
    {
        if (!sizeof($bubble->pictures)) {
            return true;
        }
        foreach ($bubble->pictures as $picture) {
            if ($picture->width < 400 || $picture->height < 400) {
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
        return 'photo-full';
    }

    public function getEmailFields()
    {
        return [
            'author',
            'date',
            'physical_description'
        ];
    }

    public function getEmailBottomButton($url)
    {
        return [
            'label' => trans('share.bubbles.banq_photo.see_entry'),
            'url' => $url
        ];
    }
}
