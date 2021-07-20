<?php namespace Manivelle\Models;

use Illuminate\Database\Eloquent\Model;

class ChannelUser extends UserPivot
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'channels_users_pivot';
    
    protected $casts = [
        'channel_id' => 'integer',
        'user_id' => 'integer',
        'user_role_id' => 'integer'
    ];
    
    /**
     * Relationships
     */
    public function channel()
    {
        return $this->belongsTo(\Manivelle\Models\Channel::class, 'channel_id');
    }
}
