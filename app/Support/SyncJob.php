<?php

namespace Manivelle\Support;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Manivelle\Jobs\Job;

use Exception;
use Panneau;
use Cache;
use Log;
use Panneau\Exceptions\ResourceNotFoundException;

use Manivelle\Jobs\CreateImagesJob;
use Manivelle\Models\Bubble;
use Manivelle\Models\Channel;
use Carbon\Carbon;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException;

abstract class SyncJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    
    protected $resource;
    protected $channelType;
    protected $channelHandle;
    protected $bubbleType;
    protected $bubbleExpiration = -1; //in hours
    
    public function __construct()
    {
        $this->resource = Panneau::resource('bubbles');
    }
    
    protected function getExistsCacheKey()
    {
        $class = preg_replace('/[^0-9a-zA-Z\_\-]+/i', '', get_class($this));
        return 'syncjob_'.$class;
    }
    
    public function exists()
    {
        $cacheKey = $this->getExistsCacheKey();
        return Cache::has($cacheKey);
    }
    
    public function setExists($exists = true)
    {
        $cacheKey = $this->getExistsCacheKey();
        if ($exists) {
            Cache::put($cacheKey, true, 60*24*7);
        } else {
            Cache::forget($cacheKey);
        }
    }
    
    protected function getChannels()
    {
        $query = Channel::query();
        
        if (!empty($this->channelType)) {
            $query->where('type', $this->channelType);
        }
        
        if (!empty($this->channelHandle)) {
            $query->where('handle', $this->channelHandle);
        }
        
        $channels = $query->get();
        
        return $channels;
    }
    
    protected function getBubbleFromHandle($handle, $data = [])
    {
        $data = array_merge([
            'type' => $this->bubbleType,
            'handle' => $handle
        ], $data);
        
        try {
            $bubble = $this->resource->find([
                'type' => $data['type'],
                'handle' => $data['handle']
            ]);
            return $bubble;
        } catch (ResourceNotFoundException $e) {
        }
        
        return null;
    }
    
    protected function createBubble($handle, $fields = [], $data = [])
    {
        $data = array_merge([
            'type' => $this->bubbleType,
            'handle' => $handle,
            'fields' => $fields
        ], $data);
        
        $bubble = $this->getBubbleFromHandle($handle, $data);
        
        if ($bubble) {
            if ($this->bubbleExpiration !== -1) {
                $now = Carbon::now();
                $updatedDate = $bubble->updated_at;
                $hours = $now->diffInHours($updatedDate);
                if ($hours < $this->bubbleExpiration) {
                    Log::info('[SyncJob] Skip: Bubble is not expired '.$handle);
                    return false;
                }
            }
            
            $this->resource->update($bubble->id, $data);
        } else {
            $bubble = $this->resource->store($data);
        }
        
        $now = Carbon::now();
        $bubble->load('pictures');
        foreach ($bubble->pictures as $picture) {
            $hours = $now->diffInHours($picture->updated_at);
            if ($hours <= 0) {
                $this->dispatch(new CreateImagesJob($picture));
            }
        }
        
        return $bubble;
    }
    
    protected function addBubbleToChannels(Bubble $bubble)
    {
        $channels = $this->getChannels();
        
        foreach ($channels as $channel) {
            $channel->addBubble($bubble);
        }
    }
    
    protected function imageIsValid($url)
    {
        try {
            $client = new HttpClient();
            $response = $client->request('GET', $url);
        } catch (RequestException $e) {
            return false;
        } catch (Exception $e) {
            return false;
        }
        
        if ($response->getStatusCode() !== 200) {
            return false;
        }
        
        $contenType = trim(implode('', $response->getHeader('Content-Type')));
        if (!preg_match('/^image\//', $contenType)) {
            return false;
        }
        
        $image = @imagecreatefromstring($response->getBody());
        if (!$image || @imagesx($image) <= 1) {
            return false;
        }
        
        imagedestroy($image);
        
        return true;
    }
}
