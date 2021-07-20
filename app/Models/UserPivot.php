<?php namespace Manivelle\Models;

class UserPivot extends Pivot
{
    protected $fillable = [
        'user_id',
        'user_role_id'
    ];
    
    protected $casts = [
        'user_id' => 'integer',
        'user_role_id' => 'integer'
    ];
    
    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo('Manivelle\User', 'user_id');
    }
    
    public function role()
    {
        return $this->belongsTo(config('roles.models.role'), 'user_role_id');
    }
    
    public function getPivotRelationName()
    {
        return 'user';
    }
}
