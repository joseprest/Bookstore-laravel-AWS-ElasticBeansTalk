<?php namespace Manivelle\Channels\Locations;

use Manivelle\Support\ChannelType;

use Manivelle\Panneau\Fields\AuthorField;
use Manivelle\Models\Bubble;
use Panneau\Fields\Date as DateField;
use Manivelle;

class LocationsChannel extends ChannelType
{

    protected $attributes = [
        'type' => 'locations',
        'bubbleType' => 'location'
    ];

    protected $locations = [];

    public function settings()
    {
        return [];
    }

    public function filters()
    {
        return [
            [
                'name' => 'location',
                'label' => trans('channels/locations.filters.location'),
                'field' => 'select',
                'type' => 'map',
                'markerType' => 'icon',
                'clusterIconType' => 'icon',
                'values' => function () {
                    return $this->getLocationsOptions();
                }
            ],
        ];
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

    public function getLocations()
    {
        if ($this->locations) {
            return $this->locations;
        }

        $channelId = $this->model ? $this->model->id:null;

        $params = $channelId ? [
            'channel_id' => $channelId
        ]:[];

        $locations = $this->getValues('location', $params);
        foreach ($locations as $location) {
            $items[$location['value']] = $location;
        }
        $this->locations = array_values($items);

        return $this->locations;
    }

    public function getLocationsOptions()
    {
        $result = $this->getLocations();

        $items = [];
        foreach ($result as $item) {
            $items[] = [
                'label' => $item['label'],
                'value' => $item['value'],
                'position' => array_get($item, 'position')
            ];
        }

        usort($items, function ($a, $b) {
            return strcmp($a['label'], $b['label']);
        });

        return $items;
    }
}
