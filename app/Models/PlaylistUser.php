<?php namespace Manivelle\Models;

use Illuminate\Database\Eloquent\Model;

class PlaylistUser extends UserPivot
{

    protected $touches = ['playlist'];
    
    protected $casts = [
        'playlist_id' => 'integer',
        'user_id' => 'integer',
        'user_role_id' => 'integer'
    ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'playlists_users_pivot';
    
    /**
     * Relationships
     */
    public function playlist()
    {
        return $this->belongsTo(\Manivelle\Models\Playlist::class, 'playlist_id');
    }
}
