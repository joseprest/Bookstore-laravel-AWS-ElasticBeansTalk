<?php namespace Manivelle\Panneau\Resources;

use Panneau\Support\EloquentResource;

class ScreensResource extends EloquentResource
{
    protected $modelName = 'Manivelle\Models\Screen';
    
    protected function buildQuery($query, $params)
    {
        $query->with('organisations');
    }
    
    protected function scopeOrganisationId($query, $value)
    {
        $query->whereHas('organisations', function ($query) use ($value) {
            if (is_array($value)) {
                $query->whereIn('organisations.id', $value);
            } else {
                $query->where('organisations.id', $value);
            }
        });
    }
    
    protected function saveModel($model, $data)
    {
        $model->fill($data);
        
        $model->save();
        
        $model->saveFields(array_get($data, 'fields', []));
        
        return $model;
    }
}
