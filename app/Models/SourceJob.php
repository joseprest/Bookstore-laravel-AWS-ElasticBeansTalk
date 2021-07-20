<?php namespace Manivelle\Models;

use Illuminate\Database\Eloquent\Model;

class SourceJob extends Model
{
    protected $table = 'sources_jobs';
    
    public function source()
    {
        return $this->belongsTo(Source::class);
    }
    
    public function sourceSync()
    {
        return $this->belongsTo(SourceSync::class);
    }
}
