<?php

namespace Manivelle\Models;

use Illuminate\Database\Eloquent\Model;

class ScreenCache extends Model
{
    protected $table = 'screens_caches';
    
    protected $casts = [
        'screen_id' => 'integer'
    ];
    
    public function screen()
    {
        return $this->belongsTo(\Manivelle\Models\Screen::class);
    }
}
