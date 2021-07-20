<?php namespace Manivelle\Models;

use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    protected $table = 'sources';

    protected $fillable = [
        'type',
        'handle',
        'name',
        'settings'
    ];

    protected $casts = [
        'settings' => 'array'
    ];

    public function syncs()
    {
        return $this->hasMany(SourceSync::class, 'source_id');
    }

    public function jobs()
    {
        return $this->hasMany(SourceJob::class, 'source_id');
    }

    public function getSourceType()
    {
        return app('manivelle')
            ->sourceType($this->type)
            ->setSource($this);
    }

    public function isSyncing()
    {
        $sync = $this->getStartedSync();

        return $sync ? true:false;
    }

    public function getLastSync()
    {
        return $this->syncs()
                        ->where('finished', 1)
                        ->orderBy('id', 'desc')
                        ->first();
    }

    public function getCurrentSync()
    {
        return $this->syncs()
                        ->where('finished', 0)
                        ->orderBy('id', 'asc')
                        ->first();
    }

    public function getStartedSync()
    {
        return $this->syncs()
                        ->where('started', 1)
                        ->where('finished', 0)
                        ->orderBy('id', 'asc')
                        ->first();
    }

    public function getFinishedSyncs()
    {
        return $this->syncs()
                        ->where('finished', 1)
                        ->orderBy('id', 'asc')
                        ->get();
    }
}
