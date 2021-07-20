<?php namespace Manivelle\Support;

use Panneau\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    protected $channelTypes = [];
    protected $bubbleTypes = [];
    protected $sourceTypes = [];
    protected $syncJobs = [];
    
    protected $manivelle;
    
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        
        $this->registerChannelTypes();
        $this->registerBubbleTypes();
        $this->registerSourceTypes();
    }
    
    public function registerChannelTypes()
    {
        foreach ($this->channelTypes as $name => $channelType) {
            if (is_numeric($name)) {
                $this->manivelle->registerChannelType($channelType);
            } else {
                $this->manivelle->registerChannelType($channelType, $name);
            }
        }
    }
    
    public function registerBubbleTypes()
    {
        foreach ($this->bubbleTypes as $name => $bubbleType) {
            if (is_numeric($name)) {
                $this->manivelle->registerBubbleType($bubbleType);
            } else {
                $this->manivelle->registerBubbleType($bubbleType, $name);
            }
        }
    }
    
    public function registerSourceTypes()
    {
        foreach ($this->sourceTypes as $name => $source) {
            if (is_numeric($name)) {
                $this->manivelle->registerSourceType($source);
            } else {
                $this->manivelle->registerSourceType($source, $name);
            }
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        parent::register();
        
        $this->manivelle = $this->app['manivelle'];
    }
}
