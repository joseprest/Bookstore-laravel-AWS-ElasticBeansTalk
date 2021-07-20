<?php namespace Manivelle\Models;

use Illuminate\Database\Eloquent\Model;

class BubblePivot extends Pivot
{
    protected $fillable = [
        'bubble_id'
    ];
    
    protected $casts = [
        'bubble_id' => 'integer'
    ];

    /**
     * Relationships
     */
    public function bubble()
    {
        return $this->belongsTo(\Manivelle\Models\Bubble::class, 'bubble_id');
    }

    public function getPivotRelationName()
    {
        return 'bubble';
    }
}
