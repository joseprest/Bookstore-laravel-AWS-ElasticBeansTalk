<?php namespace Manivelle\Channels\Locations;

use Manivelle\Support\BubbleType;

use Manivelle\Support\Str;

class LocationBubble extends BubbleType
{
    protected $attributes = [
        'type' => 'location'
    ];

    public function attributes()
    {
        return [
            'label' => trans('bubbles/location.label')
        ];
    }

    public function fields()
    {
        return [
            [
                'name' => 'name',
                'type' => 'text',
                'label' => trans('bubbles/location.fields.name')
            ],
            [
                'name' => 'description',
                'type' => 'text',
                'label' => trans('bubbles/location.fields.description'),
                'attributes' => [
                    'type' => 'textarea'
                ]
            ],
            [
                'name' => 'link',
                'type' => 'text',
                'label' => trans('bubbles/location.fields.link')
            ],
            [
                'name' => 'picture',
                'type' => 'picture',
                'label' => trans('bubbles/location.fields.picture')
            ],
            [
                'name' => 'location',
                'type' => 'location',
                'name_from' => 'fields.name',
                'label' => trans('bubbles/location.fields.location')
            ],
            [
                'name' => 'phone',
                'type' => 'text',
                'label' => trans('bubbles/location.fields.phone')
            ],
            [
                'name' => 'email',
                'type' => 'text',
                'label' => trans('bubbles/location.fields.email')
            ],
        ];
    }

    public function snippet()
    {
        $snippet = parent::snippet();

        return array_merge($snippet, [
            'title' => function ($fields) {
                return $fields->name ? $fields->name:'';
            },
            'picture' => function ($fields) {
                return $fields->picture ? $fields->picture:null;
            },
            'description' => function ($fields) {
                return $fields->description ? $fields->description:'';
            },
            'summary' => function ($fields) {
                return $fields->description ? $fields->description:'';
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
                'name' => 'location',
                'type' => 'tokens',
                'label' => trans('bubbles/location.filters.location'),
                'queryScope' => function ($query, $value) {
                    /*$query->whereHas('metadatas', function ($query) use ($value) {
                        $query->where('mediatheque_metadatables.metadatable_position', 'location');
                        $query->where('mediatheque_metadatas.value', $value);
                    });*/

                    $query->whereHas('fields_locations', function ($query) use ($value) {
                        $query->where('fields_locations_morph_pivot.field_name', 'location');
                        $query->where('fields_locations.name', $value);
                    });
                },
                'value' => function ($bubble, $fields) {
                    $location = $fields->location && $fields->location->id ? $fields->location->id:'';

                    return Str::slug($location);
                },
                'tokens' => function ($params = []) {
                    return $this->getLocationsTokens($params);
                }
            ]
        ];
    }

    public function getLocationsTokens($params = [], $name = 'location')
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

    /**
     * Email
     */
    public function getEmailLayout()
    {
        return 'photo-small';
    }

    public function getEmailTopButton($url)
    {
        return [
            'label' => trans('share.bubbles.location.see_entry'),
            'url' => $url
        ];
    }
}
