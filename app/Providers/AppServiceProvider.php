<?php namespace Manivelle\Providers;

use Illuminate\Support\ServiceProvider;
use Storage;
use Cache;

use Pubnub\Pubnub;
use Manivelle\Broadcasters\PubnubBroadcaster;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['Illuminate\Broadcasting\BroadcastManager']->extend(
            'pubnub',
            function ($app, $config) {
                return new PubnubBroadcaster(
                    new Pubnub($config['publish_key'], $config['subscribe_key']),
                    array_get($config, 'namespace', null)
                );
            }
        );
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerPubnub();
    }

    protected function registerPubnub()
    {
        $this->app->bind(\Pubnub\Pubnub::class, function ($app) {
            $publishKey = $app['config']->get('services.pubnub.publish_key');
            $subscribeKey = $app['config']->get('services.pubnub.subscribe_key');
            return new Pubnub($publishKey, $subscribeKey);
        });
    }
}
