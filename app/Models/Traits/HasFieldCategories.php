<?php namespace Manivelle\Models\Traits;

trait HasFieldCategories
{
    public function fields_categories()
    {
        $morphName = 'fieldable';
        $key = 'field_id';
        $model = \Manivelle\Models\Fields\Category::class;
        $table = 'fields_categories_morph_pivot';
        $query = $this->morphToMany($model, $morphName, $table, null, $key)
                        ->withTimestamps()
                        ->withPivot($morphName.'_position', $morphName.'_order');

        return $query;
    }
}
