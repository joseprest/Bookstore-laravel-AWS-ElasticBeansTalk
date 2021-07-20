<?php namespace Manivelle\Models;

class ScreenPivot extends Pivot
{
    protected $fillable = [
        'screen_id',
        'settings'
    ];
    
    protected $casts = [
        'settings' => 'object',
        'screen_id' => 'integer'
    ];
    
    protected $touches = [
        'screen'
    ];
    
    /**
     * Relationships
     */
    public function screen()
    {
        return $this->belongsTo(\Manivelle\Models\Screen::class, 'screen_id');
    }
    
    public function getPivotRelationName()
    {
        return 'screen';
    }
}
