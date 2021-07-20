<?php namespace Manivelle\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PlaylistPivot extends Pivot
{
    protected $with = array();
    
    protected $casts = [
        'settings' => 'object'
    ];
}
