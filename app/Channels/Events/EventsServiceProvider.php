<?php namespace Manivelle\Channels\Events;

use Manivelle\Support\ChannelServiceProvider;

class EventsServiceProvider extends ChannelServiceProvider
{
    protected $channelTypes = [
        \Manivelle\Channels\Events\EventsChannel::class
    ];
    
    protected $bubbleTypes = [
        \Manivelle\Channels\Events\EventBubble::class
    ];
    
    protected $fields = [
        \Manivelle\Channels\Events\Fields\EventCategory::class,
        \Manivelle\Channels\Events\Fields\EventCategories::class,
        \Manivelle\Channels\Events\Fields\EventGroup::class
    ];
    
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        
        if ($this->app->bound('graphql')) {
            $this->app['graphql']->addType(\Manivelle\Channels\Events\GraphQL\BubbleEventCategoryFieldType::class, 'BubbleEventCategoryField');
        }
    }
}
