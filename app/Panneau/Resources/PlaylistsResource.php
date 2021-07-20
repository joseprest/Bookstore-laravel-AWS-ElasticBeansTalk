<?php namespace Manivelle\Panneau\Resources;

use Panneau\Support\EloquentResource;

class PlaylistsResource extends EloquentResource
{
    protected $modelName = 'Manivelle\Models\Playlist';
    
    protected function buildQuery($query, $params)
    {
        $query->with('organisation', 'screens', 'bubbles');
    }
    
    protected function scopeScreenId($query, $value, $params)
    {
        $query->whereHas('screens', function ($query) use ($value, $params) {
            if (isset($params['organisation_id'])) {
                $query->where('organisation_id', $params['organisation_id']);
            }
            
            if (is_array($value)) {
                $query->whereIn('screens.id', $value);
            } else {
                $query->where('screens.id', $value);
            }
        });
    }
    
    protected function scopeOrganisationId($query, $value, $params)
    {
        if (!isset($params['screen_id'])) {
            $query->where('organisation_id', $value);
        }
    }
    
    public function findFromOrganisationAndScreen($organisationId, $screenId)
    {
        return $this->find(array(
            'organisation_id' => $organisationId,
            'screen_id' => $screenId
        ));
    }
}
