<?php

namespace Manivelle\Sources;

use Illuminate\Queue\Connectors\SyncConnector;

class QueueSyncConnector extends SyncConnector
{
    /**
     * Establish a queue connection.
     *
     * @param  array  $config
     * @return \Illuminate\Contracts\Queue\Queue
     */
    public function connect(array $config)
    {
        return new QueueSync;
    }
}
