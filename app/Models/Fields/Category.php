<?php namespace Manivelle\Models\Fields;

class Category extends Field
{
    protected $table = 'fields_categories';
    
    protected $fillable = [
        'external_id',
        'name'
    ];
    
    protected function getValueAttribute($value)
    {
        return [
            'value' => $this->external_id,
            'label' => $this->name
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
