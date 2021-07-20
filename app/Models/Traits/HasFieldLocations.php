<?php namespace Manivelle\Models\Traits;

trait HasFieldLocations
{
    public function fields_locations()
    {
        $morphName = 'fieldable';
        $key = 'field_id';
        $model = \Manivelle\Models\Fields\Location::class;
        $table = 'fields_locations_morph_pivot';
        $query = $this->morphToMany($model, $morphName, $table, null, $key)
                        ->withTimestamps()
                        ->withPivot($morphName.'_position', $morphName.'_order');

        return $query;
    }
}
