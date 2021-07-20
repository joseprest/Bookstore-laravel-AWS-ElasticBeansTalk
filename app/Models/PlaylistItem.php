<?php namespace Manivelle\Models;

use Illuminate\Database\Eloquent\Model;

use Event;
use Manivelle\Events\PlaylistUpdated;

class PlaylistItem extends Model
{
    protected $table = 'playlists_bubbles_pivot';
    
    protected $touches = [
        'playlist'
    ];
    
    protected $fillable = [
        'bubble_id',
        'condition_id',
        'order'
    ];
    
    protected $visible = [
        'id',
        'bubble_id',
        'condition_id',
        'order',
        'bubble',
        'condition'
    ];
    
    protected $casts = [
        'settings' => 'object',
        'bubble_id' => 'integer',
        'condition_id' => 'integer'
    ];
    
    /**
     * Boot
     */
    protected static function boot()
    {
        parent::boot();
    }
    
    public function condition()
    {
        return $this->belongsTo('Manivelle\Models\Condition', 'condition_id');
    }
    
    public function playlist()
    {
        return $this->belongsTo('Manivelle\Models\Playlist', 'playlist_id');
    }
    
    public function bubble()
    {
        return $this->belongsTo('Manivelle\Models\Bubble', 'bubble_id');
    }
}
