<?php

namespace Manivelle\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        'Folklore\LaravelLocale\LocaleChanged' => [
            'Manivelle\Listeners\LocaleEventListener'
        ]
    ];
    
    protected $subscribe = [
        \Manivelle\Listeners\ClearViewComposerCachesSubscriber::class,
        \Manivelle\Listeners\ModelSubscriber::class,
        \Manivelle\Listeners\PlaylistSubscriber::class,
        \Manivelle\Listeners\BubbleSubscriber::class,
        \Manivelle\Listeners\ScreenSubscriber::class,
        \Manivelle\Listeners\SourceSubscriber::class,
        \Manivelle\Listeners\OrganisationSubscriber::class,
        \Manivelle\Listeners\ManivelleSubscriber::class
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);
    }
}
