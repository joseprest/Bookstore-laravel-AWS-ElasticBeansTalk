<?php

namespace Manivelle\Channels\Books\Jobs;

use Panneau;
use Panneau\Exceptions\ResourceNotFoundException;

use Manivelle\Support\SyncJob;
use Illuminate\Bus\Queueable;
use Manivelle\Jobs\CreateImagesJob;

use Manivelle\Models\Bubble;
use Manivelle\Models\Channel;

use Carbon\Carbon;

class SyncBooks extends SyncJob
{
    
    protected $channelHandle = 'books';
    
    public function handle()
    {
        $libraries = config('manivelle.channels.books.sync.libraries');
        foreach ($libraries as $library) {
            $this->syncLibrary($library);
        }
    }
    
    protected function syncLibrary($library)
    {
        
        $this->output->writeln('Syncing books for '.$library.'...');
        $url = 'http://'.$library.'.pretnumerique.ca/catalog.atom';
        
        $count = 0;
        $maxCount = -1;
        $retries = 0;
        $maxRetries = 5;
        while ($url) {
            $this->output->writeln('<comment>Loading:</comment> '.$url.'...');
            
            $content = @file_get_contents($url);
            $xml = !empty($content) ? @simplexml_load_string($content):null;
            if (empty($xml)) {
                $retries++;
                if ($retries === $maxRetries) {
                    break;
                } else {
                    sleep(1 * $retries);
                    continue;
                }
            } else {
                $retries = 0;
            }
            
            foreach ($xml->entry as $entry) {
                try {
                    $content = @file_get_contents((string)$entry->id);
                    $entryXml = !empty($content) ? @simplexml_load_string($content):null;
                    if (empty($entryXml)) {
                        continue;
                    }
                    
                    $data = $this->getDataFromEntry($entryXml);
                    $bubble = $this->createBubbleFromData($data, $library);
                    if ($bubble !== false) {
                        $this->addBubbleToChannels($bubble);
                        
                        $this->output->writeln('<comment>Created:</comment> Bubble '.$data['handle'].'.');
                    }
                    $count++;
                } catch (\Exception $e) {
                    $this->output->writeln('<error>Error</error> '.$e->getMessage().' ['.$e->getFile().' Line '.$e->getLine().']');
                }
            }
            
            $url = null;
            foreach ($xml->link as $link) {
                if ((string)$link['rel'] === 'next') {
                    $url = (string)$link['href'];
                }
            }
            
            if ($maxCount !== -1 && $count >= $maxCount) {
                break;
            }
        }
    }
    
    protected function createBubbleFromData($data, $library)
    {
        try {
            $data['fields']['libraries'] = [$library];
            
            $bubble = $this->resource->find([
                'type' => 'book',
                'handle' => $data['handle']
            ]);
            
            $currentUpdatedDate = $data['fields']['updated_at'] ? Carbon::parse($data['fields']['updated_at']):null;
            $bubbleUpdateDate = $bubble->fields->updated_at ? Carbon::parse($bubble->fields->updated_at):null;
            $hasLibrary = $bubble->fields->libraries && $bubble->fields->libraries->first(function ($key, $item) use ($library) {
                return $item['key'] === $library;
            });
            if ($currentUpdatedDate && $bubbleUpdateDate && $currentUpdatedDate->lte($bubbleUpdateDate) && $hasLibrary) {
                $this->output->writeln('<info>Skip:</info> Bubble '.$data['handle'].'');
                return false;
            }
            
            $libraries = array_pluck($bubble->fields->libraries, 'key');
            if (!in_array($library, $libraries)) {
                $libraries[] = $library;
                $data['fields']['libraries'] = $libraries;
                $this->output->writeln('<info>Library:</info> Adding library '.$library.' to Bubble '.$bubble->handle.'.');
            }
            $this->resource->update($bubble->id, $data);
        } catch (ResourceNotFoundException $e) {
            $bubble = $this->resource->store($data);
        }
        $bubble = $this->resource->find($bubble->id);
        
        $this->output->writeln('<info>Created:</info> Bubble '.$bubble->handle.'.');
        
        foreach ($bubble->pictures as $picture) {
            $this->output->writeLn('<comment>Creating:</comment> Picture #'.$picture->id.' at '.$picture->link.'...');
            $this->dispatch(new CreateImagesJob($picture));
        }
        
        return $bubble;
    }
    
    protected function getDataFromEntry($entry)
    {
        
        $fields = [
            'title' => (string)$entry->title,
            'summary' => (string)$entry->summary,
            'author' => $entry->author && $entry->author->name ? (string)$entry->author->name:null,
            'category' => $entry->category && $entry->category['term'] ? (string)$entry->category['term']:null
        ];
        
        $dc = $entry->children('http://purl.org/dc/terms/');
        
        if ($dc->publisher) {
            $fields['publisher'] = (string)$dc->publisher;
        }
        
        if ($dc->language) {
            $fields['language'] = (string)$dc->language;
        }
        
        if ($dc->issued) {
            $fields['date'] = (string)$dc->issued;
        }
        
        foreach ($entry->link as $link) {
            if (isset($link['rel']) && isset($link['href']) && (string)$link['rel'] === 'http://opds-spec.org/image') {
                $fields['cover_front'] = (string)$link['href'];
            } elseif (isset($link['rel']) && isset($link['href']) && isset($link['type']) && (string)$link['rel'] === 'alternate' && (string)$link['type'] === 'text/html') {
                $fields['link'] = (string)$link['href'];
            }
        }
        
        $updatedDate = Carbon::parse((string)$entry->updated);
        $fields['updated_at'] = $updatedDate->toDateTimeString();
        
        preg_match('/^http\:\/\/([\.]+).pretnumerique\.ca\/resource_entries\/([\.]+)\.atom$/', (string)$entry->id, $matches);
        $handle = 'book_'.(isset($matches[1]) ? $matches[1]:md5((string)$entry->id));
        
        $data = [
            'type' => 'book',
            'handle' => $handle,
            'fields' => $fields
        ];
        
        return $data;
    }
}
