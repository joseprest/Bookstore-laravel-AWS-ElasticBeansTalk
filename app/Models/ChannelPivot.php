<?php namespace Manivelle\Models;

class ChannelPivot extends Pivot
{
    
    protected $fillable = [
        'channel_id',
        'settings'
    ];
    
    protected $casts = [
        'settings' => 'object',
        'channel_id' => 'integer'
    ];
    
    /**
     * Relationships
     */
    public function channel()
    {
        return $this->belongsTo(\Manivelle\Models\Channel::class);
    }
    
    public function getPivotRelationName()
    {
        return 'channel';
    }
}
