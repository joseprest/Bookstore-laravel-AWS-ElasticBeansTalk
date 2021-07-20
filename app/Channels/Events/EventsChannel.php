<?php namespace Manivelle\Channels\Events;

use Manivelle\Support\ChannelType;

use Manivelle\Channels\Events\Fields\EventGroup;
use Manivelle\Channels\Events\Fields\EventCategory;
use Manivelle\Panneau\Fields\LocationField;
use Panneau\Fields\DateRange;
use Manivelle\Models\Bubble;
use Carbon\Carbon;
use Manivelle;

class EventsChannel extends ChannelType
{

    protected $attributes = [
        'type' => 'events',
        'bubbleType' => 'event'
    ];

    protected $groups;
    protected $venues;
    protected $categories;
    protected $dates;

    public function fields()
    {
        $fields = parent::fields();

        return $fields;
    }

    public function settings()
    {
        return [];
    }

    public function views()
    {
        return [
            [
                'key' => 'calendar',
                'label' => trans('channels/events.views.calendar'),
                'props' => [
                    'view' => 'channel:main'
                ]
            ]
        ];
    }

    public function filters()
    {

        return [
            /*
            // Temporarily disabled while waiting for a solution to the missing categories from Mur Mitoyen
            // See manivelleio/issues#247 and manivelleio/issues#231
            [
                'name' => 'category',
                'label' => trans('channels/events.filters.category'),
                'field' => 'select',
                'type' => 'circles',
                'values' => function () {
                    return $this->getValues('category');
                }
            ],
            */
            [
                'name' => 'date',
                'label' => trans('channels/events.filters.date'),
                'field' => 'date',
                'type' => 'list',
                'layout' => 'calendar',
                'values' => function () {
                    return $this->getDates();
                }
            ],
        ];
    }

    protected function getDates()
    {
        $dates = $this->getValues('date');

        $items = [];
        foreach ($dates as $date) {
            $startDate = Carbon::parse($date['start']);
            $dateString = $startDate->toDateString();
            $items[$dateString] = [
                'label' => trim($startDate->formatLocalized('%e %B')),
                'value' => $dateString,
                'date' => $dateString
            ];
        }
        return array_values($items);
    }

    protected function getVenues()
    {
        $venues = $this->getValues('venue');
        $items = [];
        foreach ($venues as $venue) {
            $latitude = array_get($venue, 'position.latitude', 0);
            if (isset($venue['id']) && !empty($latitude)) {
                $items[$venue['id']] = [
                    'label' => array_get($venue, 'name'),
                    'value' => array_get($venue, 'id'),
                    'position' => array_get($venue, 'position'),
                    'city' => array_get($venue, 'city'),
                    'region' => array_get($venue, 'region')
                ];
            }
        }
        return $items;
    }
}
