<?php namespace Manivelle\Models\Fields;

class Person extends Field
{
    protected $table = 'fields_persons';
    
    protected $fillable = [
        'external_id',
        'firstname',
        'lastname',
        'birth_year',
        'death_year',
        'name',
        'order'
    ];
    
    protected function getNameAttribute($value)
    {
        if (!empty($value)) {
            return $value;
        }
        
        $name = [];
        $firstname = array_get($this->attributes, 'firstname');
        $lastname = array_get($this->attributes, 'lastname');
        if (!empty($firstname)) {
            $name[] = $firstname;
        }
        if (!empty($lastname)) {
            $name[] = $lastname;
        }
        
        return implode(' ', $name);
    }
    
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
