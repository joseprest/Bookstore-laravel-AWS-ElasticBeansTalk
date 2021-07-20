<?php namespace Manivelle\Panneau\Resources;

use Panneau\Support\EloquentResource;

class UsersResource extends EloquentResource
{
    protected $modelName = 'Manivelle\User';
    
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
    
    public function getFromOrganisation($organisationId, $page = null, $resultsPerPage = 25)
    {
        $items = $this->get(function ($query) use ($organisationId) {
            $query->whereHas('organisations', function ($query) use ($organisationId) {
                $query->where('organisations.id', $organisationId);
            });
            $query->orWhere('organisation_id', $organisationId);
        }, $page, $resultsPerPage);
        
        return $items;
    }
}
