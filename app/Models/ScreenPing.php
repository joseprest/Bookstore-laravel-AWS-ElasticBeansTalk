<?php namespace Manivelle\Models;

use Illuminate\Database\Eloquent\Model;

class ScreenPing extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'screens_pings';
    
    protected $fillable = [
        'screen_id',
        'load',
        'logs',
        'memory_total',
        'memory_free',
        'uptime',
        'timestamp'
    ];
    
    protected $hidden = [
        'updated_at'
    ];
    
    protected $casts = [
        'load' => 'array',
        'logs' => 'array',
        'screen_id' => 'integer'
    ];
    
    public function screen()
    {
        return $this->belongsTo(\Manivelle\Models\Screen::class, 'screen_id');
    }
}
