<?php namespace Manivelle\Models;

use Illuminate\Database\Eloquent\Model;

class ChannelBubble extends BubblePivot
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'channels_bubbles_pivot';

    protected $fillable = [
        'bubble_id',
        'channel_id'
    ];
    
    protected $casts = [
        'bubble_id' => 'integer',
        'channel_id' => 'integer'
    ];

    /**
     * Relationships
     */
    public function channel()
    {
        return $this->belongsTo(\Manivelle\Models\Channel::class, 'channel_id');
    }
}
