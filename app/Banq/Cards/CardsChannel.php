<?php namespace Manivelle\Banq\Cards;

use Manivelle\Support\ChannelType;
use Manivelle\Banq\Photos\PhotosChannel;

use Manivelle\Panneau\Fields\StringsField;
use Manivelle\Panneau\Fields\StringField;
use Manivelle\Panneau\Fields\LocationField;
use Manivelle\Models\Bubble;
use Panneau\Fields\Date as DateField;

use Manivelle\Support\Str;

class CardsChannel extends PhotosChannel
{

    protected $attributes = [
        'type' => 'banq_cards',
        'bubbleType' => 'banq_card'
    ];
    
    protected $authors;
    protected $years;

    public function settings()
    {
        return [];
    }
    
    public function filters()
    {
        return [
            [
                'name' => 'year',
                'label' => trans('channels/banq_cards.filters.year'),
                'field' => 'select',
                'type' => 'list',
                'layout' => 'year',
                'values' => function () {
                    return $this->getYearsOptions();
                }
            ],
            /*[
                'name' => 'location',
                'label' => trans('channels/banq_cards.filters.location'),
                'field' => 'select',
                'type' => 'map',
                'markerType' => 'preview',
                'clusterIconType' => 'preview',
                'values' => function()
                {
                    return $this->getLocationsOptions();
                }
            ],*/
            [
                'name' => 'subjects',
                'label' => trans('channels/banq_cards.filters.subjects'),
                'field' => 'select',
                'type' => 'list',
                'layout' => 'alphabetic',
                'values' => function () {
                    return $this->getValues('subjects');
                }
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
            $items[$location['id']] = $location;
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
                'label' => $item['name'],
                'value' => $item['id'],
                'position' => array_get($item, 'position')
            ];
        }
        
        usort($items, function ($a, $b) {
            return strcmp($a['label'], $b['label']);
        });
        
        return $items;
    }
}
