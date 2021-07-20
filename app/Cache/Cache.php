<?php

namespace Manivelle\Cache;

use Manivelle\Models\Screen;

use Illuminate\Database\Eloquent\Model;

use Event;

abstract class Cache
{
    protected $expires = -1;
    protected $localExpires = -1;
    protected $key = 'cache';
    protected $keySuffix = '';
    protected $cacheOnCloud = false;

    protected $cache;
    protected $cacheCloud;
    protected $item;
    protected $name;
    
    public function __construct()
    {
        $cacheDriver = config('cache.default', 'file');
        $this->cache = app('cache')->driver($cacheDriver);
        
        $this->cacheOnCloud = false;
        if ($this->cacheOnCloud) {
            $this->cacheCloud = app('cache')->driver('memcached');
        }
    }
    
    abstract public function getData();
    
    public function has()
    {
        $cacheKey = $this->getKey();
        return $this->cache->has($cacheKey) || ($this->cacheCloud && $this->cacheCloud->has($cacheKey));
    }
    
    public function forget()
    {
        $cacheKey = $this->getKey();
        $this->cache->forget($cacheKey);
        if ($this->cacheCloud) {
            $this->cacheCloud->forget($cacheKey);
        }
        
        return $this;
    }
    
    public function put($data = null)
    {
        $cacheKey = $this->getKey();
        $cacheCloud = $this->cacheCloud;
        $data = $data ? $data:$this->getData();
        
        if ($this->expires === -1) {
            if ($this->cacheCloud) {
                $this->cache->put($cacheKey, $data, $this->localExpires);
                $this->cacheCloud->forever($cacheKey, $data);
            } else {
                $this->cache->forever($cacheKey, $data);
            }
        } else {
            $this->cache->put($cacheKey, $data, $this->expires);
            if ($this->cacheCloud) {
                $this->cacheCloud->put($cacheKey, $data, $this->expires);
            }
        }
        
        return $this;
    }
    
    public function get()
    {
        $cacheKey = $this->getKey();
        $cacheCloud = $this->cacheCloud;
        
        if ($this->cache->has($cacheKey)) {
            return $this->cache->get($cacheKey);
        } elseif ($cacheCloud && $cacheCloud->has($cacheKey)) {
            return $cacheCloud->get($cacheKey);
        }
        
        $data = $this->getData();
        $this->put($data);
        
        return $data;
    }
    
    public function setItem($item)
    {
        $this->item = $item;
        
        return $this;
    }
    
    public function getItem()
    {
        return $this->item;
    }
    
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function getKey()
    {
        $parts = [];
        $parts[] = config('manivelle.core.cache_namespace', '');
        $parts[] = $this->key;
        if (!empty($this->keySuffix)) {
            $parts[] = $this->keySuffix;
        }
        if ($this->item && $this->item instanceof Model) {
            $parts[] = 'item-'.$this->item->id;
        }
        return implode('_', $parts);
    }
    
    public function setKeySuffix($keySuffix)
    {
        $this->keySuffix = $keySuffix;
        return $this;
    }
}
