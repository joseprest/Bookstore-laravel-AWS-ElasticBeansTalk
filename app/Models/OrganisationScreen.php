<?php namespace Manivelle\Models;

use Illuminate\Database\Eloquent\Model;

class OrganisationScreen extends ScreenPivot
{
    protected $table = 'organisations_screens_pivot';
    
    protected $touches = ['organisation'];
    
    protected $casts = [
        'settings' => 'object',
        'organisation_id' => 'integer',
        'screen_id' => 'integer'
    ];
    
    /**
     * Relationships
     */
    public function organisation()
    {
        return $this->belongsTo(\Manivelle\Models\Organisation::class, 'organisation_id');
    }
}
