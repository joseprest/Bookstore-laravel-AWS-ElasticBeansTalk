<?php

namespace Manivelle\Listeners\Traits;

use Manivelle\Jobs\CreateScreenCaches;

use Manivelle\Models\Bubble;
use Manivelle\Models\Playlist;
use Manivelle\Models\Screen;
use Manivelle\Models\ScreenChannel;
use Manivelle\Models\SourceSync;

trait UpdateScreenCaches
{
    protected function updateScreenCaches($model)
    {
        $screens = [];
        if ($model instanceof Screen) {
            $screens[] = $model;
        } elseif ($model instanceof ScreenChannel && $model->screen) {
            $screens[] = $model->screen;
        } elseif ($model instanceof SourceSync) {
            $source = $model->source;
            if (!$source) {
                return;
            }
            $sourceType = $source->getSourceType();
            $channels = $sourceType->getChannels();
            foreach ($channels as $channel) {
                foreach ($channel->screens as $screen) {
                    $screens[] = $screen;
                }
            }
        } elseif ($model instanceof Bubble) {
            $model->loadIfNotLoaded([
                'channels.screens'
            ]);
            foreach ($model->channels as $channel) {
                foreach ($channel->screens as $screen) {
                    $screens[] = $screen;
                }
            }
        } elseif ($model instanceof Playlist) {
            $model->loadIfNotLoaded('screens');
            $screens = $model->screens;
        }
        
        foreach ($screens as $screen) {
            dispatch(new CreateScreenCaches($screen));
        }
    }
}
