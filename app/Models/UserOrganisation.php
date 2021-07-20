<?php namespace Manivelle\Models;

use Illuminate\Database\Eloquent\Model;

class UserOrganisation extends UserPivot
{
    protected $table = 'organisations_users_pivot';
    
    protected $touches = ['organisation'];
    
    protected $casts = [
        'organisation_id' => 'integer',
        'user_id' => 'integer',
        'user_role_id' => 'integer'
    ];
    
    /**
     * Relationships
     */
    public function organisation()
    {
        return $this->belongsTo('Manivelle\Models\Organisation', 'organisation_id');
    }
    
    public function getPivotRelationName()
    {
        return 'organisation';
    }
}
