<?php

namespace Manivelle\Support;

use Manivelle\Models\Source;
use Manivelle\Models\SourceSync;
use Manivelle\Models\Channel;

abstract class SourceType
{
    protected $source;

    abstract public function createSyncJob();

    public function channelTypes()
    {
        return [];
    }

    public function channelHandles()
    {
        return [];
    }

    public function bubbleType()
    {
        return 'bubble';
    }

    public function setSource($source)
    {
        $this->source = $source;
        return $this;
    }

    public function syncJob(Source $source, SourceSync $sync)
    {
        $job = $this->createSyncJob();
        $job->setSource($source);
        $job->setSourceSync($sync);
        return $job;
    }

    public function getBubbleType($data = [])
    {
        return $this->bubbleType();
    }

    public function getChannels()
    {
        $query = Channel::query();

        $channelTypes = $this->channelTypes();
        if (sizeof($channelTypes)) {
            $query->whereIn('type', $channelTypes);
        }

        $channelHandles = $this->channelHandles();
        if (!empty($channelHandles)) {
            $query->whereIn('handle', $channelHandles);
        }

        $channels = $query->get();

        return $channels;
    }
}
