<?php namespace Manivelle\Channels\Events;

use Manivelle\Support\BubbleType;

use Carbon\Carbon;
use Manivelle;
use Manivelle\Contracts\Bubbles\Cleanable;
use Manivelle\Models\Bubble;

class EventBubble extends BubbleType implements Cleanable
{
    protected $attributes = [
        'type' => 'event'
    ];

    public function attributes()
    {
        return [
            'label' => trans('bubbles/event.label')
        ];
    }

    public function fields()
    {
        $fields = parent::fields();
        return array_merge($fields, [
            [
                'name' => 'title',
                'type' => 'string',
                'label' => trans('bubbles/event.fields.title')
            ],
            [
                'name' => 'link',
                'type' => 'link',
                'label' => trans('bubbles/event.fields.link')
            ],
            [
                'name' => 'description',
                'type' => 'text',
                'label' => trans('bubbles/event.fields.description'),
                'attributes' => [
                    'type' => 'textarea'
                ]
            ],
            [
                'name' => 'picture',
                'type' => 'picture',
                'label' => trans('bubbles/event.fields.picture')
            ],
            [
                'name' => 'venue',
                'type' => 'location',
                'label' => trans('bubbles/event.fields.venue')
            ],
            [
                'name' => 'room',
                'type' => 'text',
                'label' => trans('bubbles/event.fields.room'),
            ],
            [
                'name' => 'date',
                'type' => 'daterange',
                'label' => trans('bubbles/event.fields.date')
            ],
            [
                'name' => 'category',
                'type' => 'category',
                'label' => trans('bubbles/event.fields.category'),
                'hidden' => true,
            ],
            [
                'name' => 'subcategories',
                'type' => 'categories',
                'label' => trans('bubbles/event.fields.subcategories'),
                'hidden' => true,
            ],
            [
                'name' => 'group',
                'type' => 'category',
                'label' => trans('bubbles/event.fields.group'),
                'hidden' => true,
            ],
            [
                'name' => 'last_picture_filename',
                'type' => 'string',
                'hidden' => true,
            ]
        ]);
    }

    public function suggestions()
    {
        return [
            'venue',
            'city',
            'category'
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
                $startDate = Carbon::parse($fields->date->start);
                $endDate = Carbon::parse($fields->date->end);

                return $startDate->formatLocalized('%A %d %B');
            },
            'picture' => function ($fields) {
                return $fields->picture ? $fields->picture:null;
            },
            'description' => 'description',
            'summary' => function ($fields) {
                return mb_substr($fields->description, 0, 255).'...';
            },
            'link' => function ($fields) {
                return $fields->link;
            }
        ]);
    }

    public function filters()
    {
        $channelType = Manivelle::channelType('events');

        return [
            [
                'name' => 'category',
                'type' => 'tokens',
                'label' => trans('bubbles/event.filters.category'),
                'queryScope' => function ($query, $value) {
                    /*$query->whereHas('metadatas', function ($query) use ($value) {
                        $query->where('mediatheque_metadatables.metadatable_position', 'category[id]');
                        $query->where('mediatheque_metadatas.value', $value);
                    });*/

                    $query->whereHas('fields_categories', function ($query) use ($value) {
                        $query->where('fields_categories_morph_pivot.field_name', 'category');
                        $query->where('fields_categories.external_id', $value);
                    });
                },
                'value' => function ($bubble, $fields) {
                    return $fields->category ? $fields->category->id:null;
                },
                'tokens' => function ($params = []) {
                    return $this->getTokens('category', $params);
                }
            ],
            [
                'name' => 'group',
                'type' => 'tokens',
                'label' => trans('bubbles/event.filters.group'),
                'queryScope' => function ($query, $value) {
                    /*$query->whereHas('metadatas', function ($query) use ($value) {
                        $query->where('mediatheque_metadatables.metadatable_position', 'group[id]');
                        $query->where('mediatheque_metadatas.value', $value);
                    });*/

                    $query->whereHas('fields_categories', function ($query) use ($value) {
                        $query->where('fields_categories_morph_pivot.field_name', 'group');
                        $query->where('fields_categories.external_id', $value);
                    });
                },
                'value' => function ($bubble, $fields) {
                    return $fields->group ? $fields->group->id:null;
                },
                'tokens' => function ($params = []) {
                    return $this->getTokens('group', $params);
                }
            ],
            [
                'name' => 'venue',
                'type' => 'tokens',
                'label' => trans('bubbles/event.filters.venue'),
                'queryScope' => function ($query, $value) {
                    /*$query->whereHas('metadatas', function ($query) use ($value) {
                        $query->where('mediatheque_metadatables.metadatable_position', 'venue[id]');
                        $query->where('mediatheque_metadatas.value', $value);
                    });*/

                    $query->whereHas('fields_locations', function ($query) use ($value) {
                        $query->where('fields_locations_morph_pivot.field_name', 'venue');
                        $query->where('fields_locations.external_id', $value);
                    });
                },
                'value' => function ($bubble, $fields) {
                    return $fields->venue ? $fields->venue->id:null;
                },
                'tokens' => function ($params = []) {
                    return $this->getTokens('venue', $params);
                }
            ],
            [
                'name' => 'date',
                'type' => 'date',
                'label' => trans('bubbles/event.filters.date'),
                'multiple' => true,
                'queryScope' => function ($query, $value) {
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
                            if (!empty($dates[0])) {
                                $startDate = Carbon::parse($dates[0])->toDateTimeString();
                            }
                            if (isset($dates[1]) && !empty($dates[1])) {
                                $endDate = Carbon::parse($dates[1])->setTime(23, 59, 59)->toDateTimeString();
                            } else {
                                $endDate = Carbon::parse($dates[0])->setTime(23, 59, 59)->toDateTimeString();
                            }
                            break;
                    }
                    if ($startDate && $endDate) {
                        $query->whereHas('metadatas', function ($query) use ($startDate, $endDate) {
                            $query->where(function ($query) use ($startDate, $endDate) {
                                $query->where(function ($query) use ($startDate, $endDate) {
                                    $query->where('mediatheque_metadatas.name', 'date[start]');
                                    $query->where('mediatheque_metadatas.value_datetime', '>=', $startDate);
                                    $query->where('mediatheque_metadatas.value_datetime', '<=', $endDate);
                                });
                                $query->orWhere(function ($query) use ($startDate, $endDate) {
                                    $query->where('mediatheque_metadatas.name', 'date[end]');
                                    $query->where('mediatheque_metadatas.value_datetime', '>=', $startDate);
                                    $query->where('mediatheque_metadatas.value_datetime', '<=', $endDate);
                                });
                            });
                        });
                    } elseif ($startDate) {
                        $query->whereHas('metadatas', function ($query) use ($startDate, $future) {
                            $query->where(function ($query) use ($startDate, $future) {
                                $query->where(function ($query) use ($startDate, $future) {
                                    $query->where('mediatheque_metadatas.name', 'date[start]');
                                    $query->where('mediatheque_metadatas.value_datetime', $future ? '>=':'=', $startDate);
                                });
                                $query->orWhere(function ($query) use ($startDate, $future) {
                                    $query->where('mediatheque_metadatas.name', 'date[end]');
                                    $query->where('mediatheque_metadatas.value_datetime', $future ? '>=':'=', $startDate);
                                });
                            });
                        });
                    }
                },
                'value' => function ($bubble, $fields) {
                    $dateStart = isset($fields->date) && isset($fields->date->start) ? Carbon::parse($fields->date->start) : null;
                    $dateEnd = isset($fields->date) && isset($fields->date->end) ? Carbon::parse($fields->date->end) : null;
                    $days = !is_null($dateStart) && !is_null($dateEnd) ? $dateEnd->diffInDays($dateStart) : 0;
                    if ($days === 0) {
                        return !is_null($dateStart) ? [$dateStart->toDateString()]:null;
                    }
                    $dates = [];
                    for ($i = 0; $i < $days; $i++) {
                        $dates[] = $dateStart->copy()->addDays($i)->toDateString();
                    }
                    return $dates;
                }
            ],
            [
                'name' => 'city',
                'type' => 'tokens',
                'label' => trans('bubbles/event.filters.city'),
                'queryScope' => function ($query, $value) {
                    /*$query->whereHas('metadatas', function ($query) use ($value) {
                        $query->where('mediatheque_metadatas.name', 'venue[city]');
                        $query->where('mediatheque_metadatas.value', 'LIKE', $value);
                    });*/

                    $query->whereHas('fields_locations', function ($query) use ($value) {
                        $query->where('fields_locations_morph_pivot.field_name', 'venue');
                        $query->where('fields_locations.city', 'LIKE', $value);
                    });
                },
                'value' => function ($bubble, $fields) {
                    return $fields->venue ? $fields->venue->city:null;
                },
                'tokens' => function ($params = []) use ($channelType) {

                    $locations = $this->getValues('venue');

                    $cities = [];
                    foreach ($locations as $location) {
                        $city = array_get($location, 'city');
                        if (!empty($city)) {
                            $cities[] = [
                                'label' => $city,
                                'value' => $city
                            ];
                        }
                    }

                    return $cities;
                }
            ]
        ];
    }

    public function shouldCleanBubble(Bubble $bubble)
    {
        $date = isset($bubble->fields->date) ? $bubble->fields->date:null;
        if (!$date || (!$date->start && !$date->end)) {
            return true;
        }
        $start = $date->start ? Carbon::parse($date->start):null;
        $end = $date->end ? Carbon::parse($date->end):null;
        return (!$start || $start->isPast()) && (!$end || $end->isPast()) ? true:false;
    }

    /**
     * Email
     */
    public function getEmailFields()
    {
        return [
            'category',
            'venue',
            'date'
        ];
    }

    public function getEmailTopButton($url)
    {
        return [
            'label' => trans('share.bubbles.event.see_entry'),
            'url' => $url
        ];
    }

    public function getEmailBottomButton($url)
    {
        return [
            'label' => trans('share.bubbles.event.see_more'),
            'url' => trans('share.bubbles.event.see_more_url')
        ];
    }
}
