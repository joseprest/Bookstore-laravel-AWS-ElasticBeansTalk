<?php namespace Manivelle\Panneau\Resources;

use Panneau\Support\EloquentResource;

class ConditionsResource extends EloquentResource
{
    protected $modelName = 'Manivelle\Models\Condition';
    
    protected function buildQuery($query, $params)
    {
    }
    
    protected function scopeOrganisationId($query, $value, $params)
    {
        if (is_array($value)) {
            $query->whereIn('organisation_id', $value);
        } else {
            $query->where('organisation_id', $value);
        }
    }
}
