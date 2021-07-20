<?php

namespace Manivelle\Sources\MurMitoyen\Jobs;

use Manivelle\Support\SyncJob;

use Panneau;
use Exception;
use Panneau\Exceptions\ResourceNotFoundException;

use Manivelle\Models\Bubble;
use Manivelle\Models\Channel;

use Carbon\Carbon;
use Illuminate\Log\Writer;

class SyncEventsPage extends SyncMurMitoyen
{
    public $url;
    public $isLastPage;
    
    protected $shouldNeverSkip = true;
    
    public function __construct($url, $isLastPage = false)
    {
        $this->url = $url;
        $this->isLastPage = $isLastPage;
    }
    
    public function sync()
    {
        $eventsData = $this->loadJSON($this->url);
        $events = $eventsData['donnees'];
        
        if (!$eventsData || !isset($eventsData['donnees'])) {
            throw new Exception('[Source murmitoyen] Page not loaded: '.$this->url);
        }
        
        $eventsCount = sizeof($events);
        for ($i = 0; $i < $eventsCount; $i++) {
            $event = $events[$i];
            $isLastEvent = $this->isLastPage && $i === $eventsCount-1 ? true:false;
            
            try {
                $id = array_get($event, 'id');
                $job = new SyncEvent($id, $isLastEvent);
            
                /*$picture = array_get($event, 'image');
                $handle = $this->getHandleFromData($event);
                $bubble = $this->getBubbleFromHandle($handle);
                $lastPictureFilename = $bubble && isset($bubble->fields->last_picture_filename) ? $bubble->fields->last_picture_filename:null;
                if($picture && $lastPictureFilename && basename($picture) === $lastPictureFilename)
                {
                    throw new Exception('Skip');
                }*/
                
                $this->dispatch($job);
            } catch (Exception $e) {
                $message = $e->getMessage();
                
                if ($isLastEvent) {
                    $this->finish();
                }
                
                if (preg_match('/Skip/', $message)) {
                    $this->output('<info>Job skipped:</info> '.$job->getSourceJobKey());
                }
            }
        }
    }
    
    public function getSourceJobKey()
    {
        $key = parent::getSourceJobKey();
        $page = 1;
        if (preg_match('/\/([0-9]+)$/', $this->url, $matches)) {
            $page = (int)$matches[1];
        }
        return $key.'_p_'.$page;
    }
}
