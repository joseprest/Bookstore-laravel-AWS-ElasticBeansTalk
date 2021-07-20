<?php

namespace Manivelle;

use Illuminate\Database\Eloquent\Model;

use Manivelle\Events\BubbleTypeRegistered;
use Manivelle\Events\ChannelTypeRegistered;

class Manivelle
{
    protected $app;
    protected $panneau;
    
    public function __construct($app)
    {
        $this->app = $app;
        $this->panneau = $this->app['panneau'];
    }
    
    /**
     * Cache
     */
    public function registerCache($class, $eloquent = null, $key = null)
    {
        $eloquent = is_string($eloquent) ? $eloquent:get_class($eloquent);
        $key = $eloquent.($key ? ('\\'.$key):$key);
        
        return $this->register('caches', $class, $key);
    }
    
    public function cache($item, $key = null)
    {
        $class = is_string($item) ? $item:get_class($item);
        $key = $class.($key ? ('\\'.$key):$key);
        
        $cache = $this->make('caches', $key);
        
        $cache->setName($key);
        
        if ($item && $item instanceof Model) {
            $cache->setItem($item);
        }
        
        return $cache;
    }
    
    public function caches($item = null)
    {
        $caches = $this->getRegister('caches');
        
        if (!$item) {
            return $caches;
        }
        
        $itemCaches = [];
        $class = is_string($item) ? $item:get_class($item);
        foreach ($caches as $cache) {
            $normalizedClass = $this->normalizeKey($class);
            if (preg_match('/^'.preg_quote($normalizedClass).'/', $cache)) {
                $itemCaches[] = $cache;
            }
        }
        
        return $itemCaches;
    }
    
    /**
     * Source
     */
    public function registerSourceType($class, $key = null)
    {
        return $this->register('source', $class, $key);
    }
    
    public function sourceType($key)
    {
        return $this->make('source', $key);
    }
    
    public function sourceTypes()
    {
        return $this->getRegister('source');
    }
    
    /**
     * Screen type
     */
    public function registerScreenType($class, $key = null)
    {
        return $this->register('screens', $class, $key);
    }
    
    public function screenType($key)
    {
        return $this->make('screens', $key);
    }
    
    public function screenTypes()
    {
        return $this->getRegister('screens');
    }
    
    /**
     * Channel type
     */
    public function registerChannelType($class, $key = null)
    {
        $return = $this->register('channels', $class, $key);
        
        $channelType = is_object($class) ? $class:app($class);
        if (!$key) {
            $key = $channelType->type;
        }
        
        $this->app['events']->fire(new ChannelTypeRegistered($channelType, $key));
        
        return $return;
    }
    
    public function channelType($key)
    {
        return $this->make('channels', $key);
    }
    
    public function channelTypes()
    {
        return $this->getRegister('channels');
    }
    
    /**
     * Bubble type
     */
    public function registerBubbleType($class, $key = null)
    {
        $this->panneau->registerBubbleType($class, $key);
        
        $bubbleType = is_object($class) ? $class:app($class);
        if (!$key) {
            $key = $bubbleType->type;
        }
        
        $this->app['events']->fire(new BubbleTypeRegistered($bubbleType, $key));
    }
    
    /**
     * Jobs
     */
    public function registerSyncJob($class, $key = null)
    {
        return $this->register('sync_jobs', $class, $key);
    }
    
    public function syncJob($key)
    {
        return $this->make('sync_jobs', $key);
    }
    
    public function syncJobs()
    {
        return $this->getRegister('sync_jobs');
    }
    
    protected function normalizeKey($key)
    {
        return strtolower(studly_case($key));
    }
    
    /**
     * Use panneau for everything else
     */
    public static function __callStatic($method, $parameters)
    {
        return call_user_func_array(array('\Panneau\Panneau', $method), $parameters);
    }

    public function __call($method, $parameters)
    {
        return call_user_func_array(array($this->panneau, $method), $parameters);
    }
}
