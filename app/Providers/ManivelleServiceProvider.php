<?php namespace Manivelle\Providers;

use Panneau\Support\ServiceProvider;
use Manivelle\Cache\DiskStore;

use Manivelle\Manivelle;

class ManivelleServiceProvider extends ServiceProvider
{
    protected $caches = [
        
    ];
    
    public function boot()
    {
        parent::boot();
        
        $this->bootLocale();
        
        $this->bootTypes();
        
        $this->bootCaches();
        
        $this->bootSources();
    }
    
    protected function bootTypes()
    {
        $manivelle = $this->app['manivelle'];
        $manivelle->registerScreenType(\Manivelle\Support\ScreenType::class, 'screen');
        $manivelle->registerChannelType(\Manivelle\Support\ChannelType::class, 'channel');
        $manivelle->registerBubbleType(\Manivelle\Bubbles\Bubble::class, 'bubble');
        $manivelle->registerBubbleType(\Manivelle\Bubbles\Filter::class, 'filter');
    }
    
    protected function bootLocale()
    {
        $this->app['events']->listen('locale.changed', function ($locale) {
            if ($locale === 'en') {
                setlocale(LC_ALL, 'en_CA', 'en_CA.UTF-8', 'en_CA.utf8', 'enc', 'en_US', 'en', 'english');
            } elseif ($locale === 'fr') {
                setlocale(LC_ALL, 'fr_CA.utf8', 'fr_CA.UTF-8', 'fr_CA', 'frc', 'fr_FR', 'fr', 'french', 'fr-CA');
                setlocale(LC_NUMERIC, 'en_CA', 'en_CA.UTF-8', 'en_CA.utf8', 'enc', 'en_US', 'en', 'english');
            }
        });
        
        if ($this->app->runningInConsole()) {
            $this->app->setLocale(config('app.locale'));
        }
    }
    
    protected function bootCaches()
    {
        $this->app['cache']->extend('cloud', function ($app) {
            $store = new DiskStore($app['filesystem']->cloud(), 'cache');
            return $app['cache']->repository($store);
        });
        
        $manivelle = $this->app['manivelle'];
        
        $manivelle->registerCache(\Manivelle\Cache\ScreenBubblesIds::class, \Manivelle\Models\Screen::class, 'bubbles_ids');
        $manivelle->registerCache(\Manivelle\Cache\ScreenChannels::class, \Manivelle\Models\Screen::class, 'channels');
        $manivelle->registerCache(\Manivelle\Cache\ScreenTimeline::class, \Manivelle\Models\Screen::class, 'timeline');
        $manivelle->registerCache(\Manivelle\Cache\ScreenStats::class, 'screen_stats');
        //$manivelle->registerCache(\Manivelle\Cache\BubbleJson::class, \Manivelle\Models\Bubble::class, 'json');
        $manivelle->registerCache(\Manivelle\Cache\BubblePageJson::class, \Manivelle\Models\Bubble::class, 'page_json');
        $manivelle->registerCache(\Manivelle\Cache\ChannelBubblesFilters::class, \Manivelle\Models\Channel::class, 'bubbles_filters');
        $manivelle->registerCache(\Manivelle\Cache\ChannelFilters::class, \Manivelle\Models\Channel::class, 'filters');
        $manivelle->registerCache(\Manivelle\Cache\BubbleSuggestions::class, \Manivelle\Models\Bubble::class, 'suggestions');
        
        $manivelle->registerCache(\Manivelle\Cache\FilterValues::class, \Manivelle\Support\ChannelType::class, 'filter_values');
        $manivelle->registerCache(\Manivelle\Cache\FilterValues::class, \Manivelle\Support\ChannelType::class, 'filter_tokens');
        $manivelle->registerCache(\Manivelle\Cache\FilterValues::class, \Manivelle\Support\ChannelType::class, 'bubble_filter_values');
        $manivelle->registerCache(\Manivelle\Cache\FilterTokens::class, \Manivelle\Support\ChannelType::class, 'Bubble_filter_tokens');
    }
    
    public function bootSources()
    {
        $sources = config('manivelle.core.sources');
        $manivelle = $this->app['manivelle'];
        foreach ($sources as $key => $source) {
            if (is_numeric($key)) {
                $manivelle->registerSourceType($source);
            } else {
                $manivelle->registerSourceType($source, $key);
            }
        }
    }
    
    /**
     * Register
     *
     * @return void
     */
    public function register()
    {
        parent::register();
        
        $this->registerManivelle();
    }
    
    /**
     * Register manivelle
     *
     * @return void
     */
    protected function registerManivelle()
    {
        $this->app->singleton('manivelle', function ($app) {
            return new Manivelle($app);
        });
    }
}
