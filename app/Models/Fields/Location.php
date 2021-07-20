<?php namespace Manivelle\Models\Fields;

class Location extends Field
{
    protected $table = 'fields_locations';
    
    protected $fillable = [
        'external_id',
        'name',
        'address',
        'postalcode',
        'city',
        'region',
        'country',
        'latitude',
        'longitude'
    ];
    
    protected function getValueAttribute($value)
    {
        return [
            'value' => $this->external_id,
            'label' => $this->name,
            'position' => [
                'latitude' => array_get($this->attributes, 'latitude'),
                'longitude' => array_get($this->attributes, 'longitude')
            ],
            'city' => array_get($this->attributes, 'city'),
            'region' => array_get($this->attributes, 'region')
        ];
    }
    
    protected function getTokenAttribute($value)
    {
        return [
            'value' => $this->external_id,
            'label' => $this->name
        ];
    }
}
