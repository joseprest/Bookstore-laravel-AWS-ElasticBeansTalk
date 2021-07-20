<?php namespace Manivelle\Models;

use Illuminate\Database\Eloquent\Model;

use Event;
use Carbon\Carbon;
use Manivelle\Events\SourceSyncStarted;
use Manivelle\Events\SourceSyncStopped;
use Manivelle\Events\SourceSyncFinished;

class SourceSync extends Model
{
    protected $table = 'sources_syncs';
    
    protected $fillable = [];
    
    protected $casts = [
        'source_id' => 'integer',
        'state' => 'array'
    ];
    
    public function source()
    {
        return $this->belongsTo(Source::class);
    }
    
    public function jobs()
    {
        return $this->hasMany(SourceJob::class, 'source_sync_id');
    }
    
    public function isStarted()
    {
        return $this->fresh()->started;
    }
    
    public function isFinished()
    {
        return $this->fresh()->finished;
    }
    
    public function start()
    {
        $this->started = 1;
        $this->started_at = Carbon::now()->toDateTimeString();
        $this->save();
        
        $dispatcher = self::getEventDispatcher();
        if ($dispatcher) {
            $dispatcher->fire(new SourceSyncStarted($this));
        }
    }
    
    public function stop()
    {
        $this->started = 0;
        $this->save();
        
        $dispatcher = self::getEventDispatcher();
        if ($dispatcher) {
            $dispatcher->fire(new SourceSyncStopped($this));
        }
    }
    
    public function finish()
    {
        $this->finished = 1;
        $this->finished_at = Carbon::now()->toDateTimeString();
        $this->save();
        
        $dispatcher = self::getEventDispatcher();
        if ($dispatcher) {
            $dispatcher->fire(new SourceSyncFinished($this));
        }
    }
    
    public function stopAndFinish()
    {
        $this->started = 0;
        $this->finished = 1;
        $this->finished_at = Carbon::now()->toDateTimeString();
        $this->save();
        
        $dispatcher = self::getEventDispatcher();
        if ($dispatcher) {
            $dispatcher->fire(new SourceSyncFinished($this));
        }
    }
    
    public function deleteUnreservedJobs()
    {
        return $this->jobs()->where('reserved', 0)->delete();
    }
    
    public function addSyncedJob($key)
    {
        $model = $this->fresh();
        $state = $model->state;
        $jobs_synced = array_get($state, 'jobs_synced', []);
        if (!in_array($key, $jobs_synced)) {
            $jobs_synced[] = $key;
            array_set($state, 'jobs_synced', $jobs_synced);
            $model->state = $state;
            $model->save();
        }
    }
    
    public function isJobSynced($key, $fresh = true)
    {
        $model = $fresh ? $this->fresh():$this;
        
        $jobs_synced = array_get($model->state, 'jobs_synced', []);
        return in_array($key, $jobs_synced);
    }
    
    public function isJobsSynced(array $keys, $fresh = true)
    {
        $allSynced = true;
        foreach ($keys as $key) {
            if (!$this->isJobSynced($key, $fresh)) {
                $allSynced = false;
                break;
            }
        }
        
        return $allSynced;
    }
    
    public function isJobSyncing($key)
    {
        $job = $this->jobs()
                    ->where('source_job_key', $key)
                    ->first();
        return $job ? true:false;
    }
}
