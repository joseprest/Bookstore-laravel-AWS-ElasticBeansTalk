<?php namespace Manivelle\Providers;

use Illuminate\Support\ServiceProvider;
use Storage;
use Cache;

use Illuminate\Queue\Events\JobProcessed;
use Manivelle\Sources\QueueConnector as SourcesQueueConnector;
use Manivelle\Sources\QueueSyncConnector as SourcesQueueSyncConnector;

class QueueServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerSourcesQueue();
    }
    
    protected function registerSourcesQueue()
    {
        $this->app['queue']->addConnector('sources', function () {
            return new SourcesQueueConnector($this->app['db']);
        });
        
        $this->app['queue']->addConnector('sources_sync', function () {
            return new SourcesQueueSyncConnector($this->app['db']);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
