<?php namespace Manivelle\Banq\Cards;

use Manivelle\Banq\Photos\PhotoBubble;

use Manivelle\Support\Str;

class CardBubble extends PhotoBubble
{
    protected $attributes = [
        'type' => 'banq_card'
    ];

    public function attributes()
    {
        return [
            'label' => trans('bubbles/banq_card.label')
        ];
    }

    public function fields()
    {
        $fields = parent::fields();
        return $fields;
    }

    public function snippet()
    {
        $snippet = parent::snippet();
        return $snippet;
    }

    public function filters()
    {

        return [
            [
                'name' => 'year',
                'type' => 'year',
                'label' => trans('bubbles/banq_card.filters.year'),
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
                'label' => trans('bubbles/banq_card.filters.subjects'),
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
            ],

            [
                'name' => 'location',
                'type' => 'tokens',
                'label' => trans('bubbles/banq_card.filters.location'),
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

                    return Str::slug(PhotoBubble::normalizeLocation($location));
                },
                'tokens' => function ($params = []) {
                    return $this->getTokens('location', $params);
                }
            ]
        ];
    }

    /**
     * Email
     */
    public function getEmailLayout()
    {
        return 'card';
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
            'label' => trans('share.bubbles.banq_card.see_entry'),
            'url' => $url
        ];
    }
}
