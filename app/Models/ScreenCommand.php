<?php namespace Manivelle\Models;

use Illuminate\Database\Eloquent\Model;

class ScreenCommand extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'screens_commands';
    
    protected $fillable = [
        'screen_id',
        'command',
        'arguments',
        'payload',
        'return_code',
        'output',
        'sended_at',
        'executed_at'
    ];
    
    protected $hidden = [
        'updated_at'
    ];
    
    protected $appends = [
        'sended',
        'executed'
    ];
    
    protected $casts = [
        'payload' => 'array',
        'arguments' => 'array',
        'screen_id' => 'integer'
    ];
    
    protected $dates = [
        'created_at',
        'updated_at',
        'executed_at',
        'sended_at'
    ];
    
    public function screen()
    {
        return $this->belongsTo(\Manivelle\Models\Screen::class, 'screen_id');
    }
    
    /**
     * Accessors and mutators
     */
    
    public function getSendedAttribute()
    {
        return !$this->sended_at ? false:true;
    }
    
    public function getExecutedAttribute()
    {
        return !$this->executed_at ? false:true;
    }
}
