<?php

namespace Manivelle\Sources;

use Illuminate\Queue\Connectors\DatabaseConnector;
use Illuminate\Support\Arr;

class QueueConnector extends DatabaseConnector
{
    
    /**
     * Establish a queue connection.
     *
     * @param  array  $config
     * @return \Illuminate\Contracts\Queue\Queue
     */
    public function connect(array $config)
    {
        return new Queue(
            $this->connections->connection(Arr::get($config, 'connection')),
            Arr::get($config, 'table', 'sources_jobs'),
            $config['queue'],
            Arr::get($config, 'expire', 60)
        );
    }
}
