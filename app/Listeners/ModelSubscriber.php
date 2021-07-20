<?php

namespace Manivelle\Listeners;

use Illuminate\Database\Eloquent\Model;
use Log;
use Manivelle\User;

use Manivelle\Events\UserChanged;

class ModelSubscriber
{
    protected $modelCreatedEvents = [
        \Manivelle\Models\Bubble::class => \Manivelle\Events\BubbleCreated::class
    ];
    
    protected $modelUpdatedEvents = [
        \Manivelle\Models\Bubble::class => \Manivelle\Events\BubbleChanged::class
    ];
    
    protected $modelChangedEvents = [
        \Manivelle\User::class => \Manivelle\Events\UserChanged::class,
        \Manivelle\Models\Organisation::class => \Manivelle\Events\OrganisationChanged::class,
        \Manivelle\Models\OrganisationUser::class => \Manivelle\Events\OrganisationUserChanged::class,
        \Manivelle\Models\Playlist::class => \Manivelle\Events\PlaylistChanged::class,
        \Manivelle\Models\Screen::class => \Manivelle\Events\ScreenChanged::class,
        \Manivelle\Models\ScreenChannel::class => \Manivelle\Events\ScreenChannelChanged::class,
        \Manivelle\Models\Channel::class => \Manivelle\Events\ChannelChanged::class,
        \Manivelle\Models\ScreenPing::class => \Manivelle\Events\ScreenPingChanged::class,
        \Manivelle\Models\ScreenCommand::class => \Manivelle\Events\ScreenCommandChanged::class,
    ];
    
    protected $modelDeletingEvents = [
        \Manivelle\Models\Playlist::class => \Manivelle\Events\PlaylistDeleting::class,
    ];
    
    protected $modelDeletedEvents = [
        \Manivelle\Models\Bubble::class => \Manivelle\Events\BubbleDeleted::class,
    ];
    
    public function onModelCreated(Model $model)
    {
        foreach ($this->modelCreatedEvents as $modelName => $eventClass) {
            if ($model instanceof $modelName) {
                app('events')->fire(new $eventClass($model));
            }
        }
        
        $this->onModelChanged($model);
    }
    
    public function onModelUpdated(Model $model)
    {
        foreach ($this->modelUpdatedEvents as $modelName => $eventClass) {
            if ($model instanceof $modelName) {
                app('events')->fire(new $eventClass($model));
            }
        }
        
        $this->onModelChanged($model);
    }
    
    public function onModelChanged(Model $model)
    {
        foreach ($this->modelChangedEvents as $modelName => $eventClass) {
            if ($model instanceof $modelName) {
                app('events')->fire(new $eventClass($model));
            }
        }
    }
    
    public function onModelDeleting(Model $model)
    {
        foreach ($this->modelDeletingEvents as $modelName => $eventClass) {
            if ($model instanceof $modelName) {
                app('events')->fire(new $eventClass($model));
            }
        }
    }
    
    public function onModelDeleted(Model $model)
    {
        foreach ($this->modelDeletedEvents as $modelName => $eventClass) {
            if ($model instanceof $modelName) {
                app('events')->fire(new $eventClass($model));
            }
        }
    }
    
    public function subscribe($events)
    {
        $events->listen(
            'eloquent.created: *',
            '\Manivelle\Listeners\ModelSubscriber@onModelCreated'
        );
        
        $events->listen(
            'eloquent.updated: *',
            '\Manivelle\Listeners\ModelSubscriber@onModelUpdated'
        );
        
        $events->listen(
            'eloquent.deleting: *',
            '\Manivelle\Listeners\ModelSubscriber@onModelDeleting'
        );
        
        $events->listen(
            'eloquent.deleted: *',
            '\Manivelle\Listeners\ModelSubscriber@onModelDeleted'
        );
    }
}
