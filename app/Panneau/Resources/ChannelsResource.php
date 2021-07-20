<?php namespace Manivelle\Panneau\Resources;

use Panneau\Support\EloquentResource;

class ChannelsResource extends EloquentResource
{
    protected $modelName = 'Manivelle\Models\Channel';
    
    protected function buildQuery($query, $params)
    {
    }
    
    protected function scopeId($query, $value)
    {
        if (is_array($value)) {
            $query->whereIn('id', $value);
        } else {
            $query->where('id', $value);
        }
    }
    
    protected function scopeHandle($query, $value)
    {
        if (is_array($value)) {
            $query->whereIn('handle', $value);
        } else {
            $query->where('handle', $value);
        }
    }
    
    protected function scopeScreenId($query, $value)
    {
        $query->whereHas('screens', function ($query) use ($value) {
            if (is_array($value)) {
                $query->whereIn('screens.id', $value);
            } else {
                $query->where('screens.id', $value);
            }
        });
    }
    
    protected function scopeForOrganisation($query, $value)
    {
        $query->where(function ($query) use ($value) {
            $query->where('organisation_id', '0');
            $query->orWhere('organisation_id', $value);
        });
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
        
        if (isset($data['fields'])) {
            $model->saveFields($data['fields']);
        }
    }
}
