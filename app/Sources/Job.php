<?php

namespace Manivelle\Sources;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;

use Symfony\Component\Console\Output\ConsoleOutput;
use Illuminate\Log\Writer;
use Log;

use Manivelle\Jobs\Job as BaseJob;
use Manivelle\Sources\Traits\HandleBubbles;

abstract class Job extends BaseJob implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels, DispatchesJobs, HandleBubbles {
        DispatchesJobs::dispatch as originalDispatch;
    }

    public $source;
    public $sourceSync;
    public $isLast = false;

    public $connection = 'sources';

    protected $sourceJobKey;
    protected $output;
    protected $log;
    protected $shouldNeverSkip = false;
    protected $shouldNeverFinish = false;

    abstract public function sync();

    public function handle(Writer $log, ConsoleOutput $output)
    {
        $this->setLog($log);
        $this->setOutput($output);

        $key = $this->getSourceJobKey();

        if ($this->shouldStop()) {
            $output->writeLn('<info>Job stopped:</info> '.$key);
            $this->delete();
            return;
        }

        if ($this->shouldSkip()) {
            $this->skip();
            return;
        }

        $output->writeLn('<info>Job syncing:</info> '.$key);

        try {
            $this->sync();
        } catch (\Exception $e) {
            $output->writeLn('<info>Job error:</info> '.$e->getMessage());
            Log::error($e);
            $this->job->release();
        }

        //If job is released, stop here
        if (!$this->job || $this->job->isReleased()) {
            $output->writeLn('<info>Job released:</info> '.$key);
            return;
        }

        //Add to synced jobs
        $this->sourceSync->addSyncedJob($key);

        //Delete the job
        $this->delete();
        $output->writeLn('<info>Job completed:</info> '.$key);

        //If current sync should finish
        if ($this->shouldFinish()) {
            $this->finish();
            if ($this->sourceSync && $this->source) {
                $output->writeLn('<info>Sync finished:</info> #'.$this->sourceSync->id.' for '.$this->source->name);
            }
        //If current sync if finished and processing last jobs
        } elseif ($this->shouldStopIfFinished()) {
            $this->stopAndFinish();
        }
    }

    public function dispatch($job)
    {
        if ($this->sourceSync && $this->sourceSync->isFinished()) {
            $this->output('<info>Dispatch skipped:</info> Sync is finished.');
            return;
        }

        if ($job instanceof Job) {
            $key = $job->getSourceJobKey();
            $job->setSource($this->source);
            $job->setSourceSync($this->sourceSync);

            $existingCount = $this->sourceSync->jobs()
                        ->where('source_job_key', $key)
                        ->count();

            if ($existingCount || $job->shouldSkip()) {
                $this->output('<info>Dispatch job skipped:</info> '.$key);
                $job->skip();
                return;
            }
        }

        $this->originalDispatch($job);

        $this->output('<info>Job dispatched:</info> '.(isset($key) ? $key:get_class($job)));
    }

    public function shouldStop()
    {
        if (!$this->sourceSync || !$this->sourceSync->id || !$this->sourceSync->isStarted()) {
            return true;
        }

        return false;
    }

    public function shouldStopIfFinished()
    {
        if ($this->sourceSync->isFinished() && !$this->sourceSync->jobs()->count()) {
            return true;
        }

        return false;
    }

    public function shouldSkip()
    {
        $key = $this->getSourceJobKey();
        if (!$key) {
            return false;
        }

        //Check if there is an existing task
        if ($this->job &&
            $this->job instanceof \Illuminate\Queue\Jobs\DatabaseJob &&
            $job = $this->job->getDatabaseJob()
        ) {
            $existingCount = $this->sourceSync->jobs()
                        ->where('id', '!=', $job->id)
                        ->where('source_job_key', $key)
                        ->count();
            if ($existingCount) {
                return true;
            }
        }

        if ($this->shouldNeverSkip) {
            return false;
        }

        return $this->sourceSync->isJobSynced($key);
    }

    public function shouldFinish()
    {
        $sourceSync = $this->getSourceSync();
        $driver = config('queue.connections.sources.driver');
        return !$this->shouldNeverFinish && (
            (isset($this->isLast) && $this->isLast) ||
            ($driver !== 'sources_sync' && $sourceSync && $sourceSync->jobs()->count() === 0)
        );
    }

    public function skip()
    {
        $key = $this->getSourceJobKey();
        $this->output('<info>Job skipped:</info> '.$key);

        if ($this->shouldFinish()) {
            $this->finish();
        }

        try {
            $this->delete();
        } catch (\Exception $e) {
            Log::error($e);
        }
    }

    public function finish()
    {
        if ($this->sourceSync) {
            $this->sourceSync->finish();
        }
    }

    public function stopAndFinish()
    {
        if ($this->sourceSync) {
            $this->sourceSync->stopAndFinish();
        }
    }

    public function output($text)
    {
        if ($this->output) {
            $this->output->writeLn($text);
        }
    }

    public function log($text, $data = [])
    {
        if ($this->log) {
            $this->log->info($text, $data);
        }
    }

    public function setOutput($output)
    {
        $this->output = $output;
    }

    public function getOutput()
    {
        return $this->output;
    }

    public function setLog($log)
    {
        $this->log = $log;
    }

    public function getLog()
    {
        return $this->log;
    }

    public function setSource($source)
    {
        $this->source = $source;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function setSourceSync($sourceSync)
    {
        $this->sourceSync = $sourceSync;
    }

    public function getSourceSync()
    {
        return $this->sourceSync;
    }

    public function getSourceJobKey()
    {
        $key = $this->sourceJobKey ? $this->sourceJobKey:get_class($this);
        return $key;
    }
}
