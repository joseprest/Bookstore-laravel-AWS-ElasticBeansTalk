<?php

namespace Manivelle\Sources;

use Illuminate\Queue\SyncQueue;

class QueueSync extends SyncQueue
{
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
        
        return json_encode([
            'job' => 'Illuminate\Queue\CallQueuedHandler@call',
            'data' => [
                'source_id' => $source ? $source->id:'',
                'source_sync_id' => $sourceSync ? $sourceSync->id:'',
                'source_job_key' => $sourceJobKey ? $sourceJobKey:'',
                'commandName' => get_class($job),
                'command' => serialize(clone $job)
            ]
        ]);
    }
}
