<?php namespace Manivelle\Panneau\Resources;

use DB;

use Manivelle\User;

use Panneau\Support\EloquentResource;

class OrganisationsResource extends EloquentResource
{
    protected $modelName = 'Manivelle\Models\Organisation';
    
    protected function buildQuery($query, $params)
    {
        //$query->with('users');
    }
    
    protected function scopeUserId($query, $value)
    {
        $query->whereHas('users', function ($query) use ($value) {
            if (is_array($value)) {
                $query->whereIn('users.id', $value);
            } else {
                $query->where('users.id', $value);
            }
        });
    }
    
    public function getFromUser($userId, $page = null, $resultsPerPage = 25)
    {
        $items = $this->get(array(
            'user_id' => $userId
        ), $page, $resultsPerPage);
        
        return $items;
    }
}
