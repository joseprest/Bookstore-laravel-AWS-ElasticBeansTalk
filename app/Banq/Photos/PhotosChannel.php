<?php namespace Manivelle\Banq\Photos;

use Manivelle\Support\ChannelType;

use Manivelle\Panneau\Fields\StringsField;
use Manivelle\Models\Bubble;
use Panneau\Fields\Date as DateField;

class PhotosChannel extends ChannelType
{

    protected $attributes = [
        'type' => 'banq_photos',
        'bubbleType' => 'banq_photo'
    ];
    
    protected $subjects;

    public function settings()
    {
        return [];
    }
    
    public function filters()
    {
        
        return [
            [
                'name' => 'year',
                'label' => trans('channels/banq_photos.filters.year'),
                'field' => 'select',
                'type' => 'list',
                'layout' => 'year',
                'values' => function () {
                    return $this->getYearsOptions();
                }
            ],
            [
                'name' => 'subjects',
                'label' => trans('channels/banq_photos.filters.subjects'),
                'field' => 'select',
                'type' => 'list',
                'layout' => 'alphabetic',
                'values' => function () {
                    return $this->getValues('subjects');
                }
            ]
            
        ];
    }
    
    public function getYearsOptions()
    {
        $result = $this->getYears();
        
        $items = [];
        foreach ($result as $item) {
            $items[] = [
                'label' => $item,
                'value' => $item
            ];
        }
        
        usort($items, function ($a, $b) {
            return strcmp($a['label'], $b['label']);
        });
        
        return $items;
    }
    
    public function getYears()
    {
        if ($this->years) {
            return $this->years;
        }
        
        $dates = $this->getTokens('date');
        
        $years = [];
        foreach ($dates as $date) {
            $date = array_get($date, 'label', $date);
            if ($date === '0000-00-00' || !is_string($date)) {
                continue;
            }
            $time = strtotime($date);
            $year = $time !== 0 ? (int)date('Y', $time):null;
            if ($year) {
                $years[] = $year;
            }
        }
        $years = array_unique($years);
        sort($years);
        $this->years = $years;
        
        return $this->years;
    }
}
