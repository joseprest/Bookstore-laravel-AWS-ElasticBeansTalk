<?php namespace Manivelle\Models\Traits;

trait HasFieldPersons
{
    public function fields_persons()
    {
        $morphName = 'fieldable';
        $key = 'field_id';
        $model = \Manivelle\Models\Fields\Person::class;
        $table = 'fields_persons_morph_pivot';
        $query = $this->morphToMany($model, $morphName, $table, null, $key)
                        ->withTimestamps()
                        ->withPivot($morphName.'_position', $morphName.'_order');

        return $query;
    }
}
