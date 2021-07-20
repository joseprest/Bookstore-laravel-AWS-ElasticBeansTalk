<?php namespace Manivelle\Models;

use Illuminate\Database\Eloquent\Model;

class OrganisationInvitation extends Model
{
    protected $table = 'organisations_invitations';
    
    protected $touches = ['organisation'];
    
    protected $fillable = [
        'organisation_id',
        'user_id',
        'email',
        'user_role_id'
    ];
    
    protected $casts = [
        'organisation_id' => 'integer',
        'user_id' => 'integer',
        'user_role_id' => 'integer'
    ];
    
    protected static function boot()
    {
        parent::boot();
        
        //Generate an invitation key, when creating a new invitation
        static::creating(function ($model) {
            if (empty($model->invitation_key)) {
                $model->invitation_key = md5(time());
            }
        });
    }
    
    /**
     * Relationships
     */
    public function organisation()
    {
        return $this->belongsTo('Manivelle\Models\Organisation', 'organisation_id');
    }
    
    public function user()
    {
        return $this->belongsTo('Manivelle\User', 'user_id');
    }
    
    public function role()
    {
        return $this->belongsTo(config('roles.models.role'), 'user_role_id');
    }
}
