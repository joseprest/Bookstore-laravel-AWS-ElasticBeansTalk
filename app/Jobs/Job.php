<?php

namespace Manivelle\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\DispatchesJobs;

abstract class Job
{
    use Queueable, DispatchesJobs;
    
    protected $output;
    
    public function setOutput($output)
    {
        $this->output = $output;
    }
}
