<?php

namespace Manivelle\Sources;

use Illuminate\Queue\DatabaseQueue;

class Queue extends DatabaseQueue
{
    /**
     * Create an array to insert for the given job.
     *
     * @param  string|null  $queue
     * @param  string  $payload
     * @param  int  $availableAt
     * @param  int  $attempts
     * @return array
     */
    protected function buildDatabaseRecord($queue, $payload, $availableAt, $attempts = 0)
    {
        if (is_string($payload)) {
            $payload = json_decode($payload, true);
        }
        $sourceId = array_get($payload, 'data.source_id');
        $sourceSyncId = array_get($payload, 'data.source_sync_id');
        $sourceJobKey = array_get($payload, 'data.source_job_key');
        
        return [
            'queue' => $queue,
            'source_id' => $sourceId,
            'source_sync_id' => $sourceSyncId,
            'source_job_key' => $sourceJobKey,
            'payload' => json_encode($payload),
            'attempts' => $attempts,
            'reserved' => 0,
            'reserved_at' => null,
            'available_at' => $availableAt,
            'created_at' => $this->getTime(),
        ];
    }
    
    /**
     * Create a payload string from the given job and data.
     *
     * @param  string  $job
     * @param  mixed   $data
     * @param  string  $queue
     * @return string
     */
    protected function createPayload($job, $data = '', $queue = null)
    {
        $source = $job instanceof Job ? $job->getSource():null;
        $sourceSync = $job instanceof Job ? $job->getSourceSync():null;
        $sourceJobKey = $job instanceof Job ? $job->getSourceJobKey():null;
        
        return [
            'job' => 'Illuminate\Queue\CallQueuedHandler@call',
            'data' => [
                'source_id' => $source ? $source->id:'',
                'source_sync_id' => $sourceSync ? $sourceSync->id:'',
                'source_job_key' => $sourceJobKey ? $sourceJobKey:'',
                'command' => serialize(clone $job)
            ]
        ];
    }
}
