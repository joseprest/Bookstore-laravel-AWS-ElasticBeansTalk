<?php namespace Manivelle\Models;

use Illuminate\Database\Eloquent\Model;

class OrganisationChannel extends ChannelPivot
{
    protected $table = 'organisations_channels_pivot';
    
    protected $touches = ['organisation'];
    
    /**
     * Relationships
     */
    public function organisation()
    {
        return $this->belongsTo(\Manivelle\Models\Organisation::class, 'organisation_id');
    }
}
