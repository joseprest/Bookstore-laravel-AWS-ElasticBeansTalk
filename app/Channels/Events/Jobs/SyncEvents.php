<?php

namespace Manivelle\Channels\Events\Jobs;

use Panneau;
use Exception;
use Panneau\Exceptions\ResourceNotFoundException;

use Manivelle\Support\SyncJob;
use Illuminate\Bus\Queueable;

use Manivelle\Models\Bubble;
use Manivelle\Models\Channel;

use Carbon\Carbon;

class SyncEvents extends SyncJob
{
    protected $channelHandle = 'events';
    protected $bubbleType = 'event';
    protected $bubbleExpiration = 48;
    
    public function handle()
    {
        $this->output->writeln('Syncing events...');
        
        $this->output->writeln('Getting pages...');
        $eventsData = $this->loadEvents(date('Y-m-d'));
        $pagesLink = $this->getPagesLinks($eventsData);
        $this->output->writeln(sizeof($pagesLink).' page(s) found.');
        
        $i = 0;
        foreach ($pagesLink as $pageLink) {
            $this->output->writeln('<info>Syncing:</info> Page #'.($i+1).'...');
            $eventsData = $this->loadJSON($pageLink);
            $events = $eventsData['donnees'];
            foreach ($events as $event) {
                try {
                    $picture = array_get($event, 'image');
                    $handle = $this->getHandleFromData($event);
                    $bubble = $this->getBubbleFromHandle($handle);
                    $lastPictureFilename = $bubble && isset($bubble->fields->last_picture_filename) ? $bubble->fields->last_picture_filename:null;
                    if ($picture && $lastPictureFilename && basename($picture) === $lastPictureFilename) {
                        throw new Exception('Skip');
                    }
                    
                    $eventData = $this->loadEvent($event['id']);
                    $fields = $this->getFieldsFromData($eventData);
                    if (!$fields) {
                        throw new Exception('Skip');
                    }
                    
                    $currentUpdatedDate = $fields['updated_at'] ? Carbon::parse($fields['updated_at']):null;
                    $bubbleUpdateDate = $bubble && $bubble->fields->updated_at ? Carbon::parse($bubble->fields->updated_at):null;
                    if ($currentUpdatedDate && $bubbleUpdateDate && $currentUpdatedDate->lte($bubbleUpdateDate) && $hasLibrary) {
                        throw new Exception('Skip');
                    }
                    
                    $bubble = $this->createBubble($handle, $fields);
                    
                    if ($bubble === false) {
                        throw new Exception('Skip');
                    }
                    
                    $this->addBubbleToChannels($bubble);
                    $this->output->writeln('<comment>Created:</comment> Bubble '.$handle.'.');
                } catch (Exception $e) {
                    $message = $e->getMessage();
                    if (preg_match('/^Skip/', $message)) {
                        $this->output->writeln('<info>Skip:</info> Bubble '.$handle.'.');
                    } else {
                        $this->output->writeln('<error>Error:</error> '.$message);
                    }
                }
            }
            $i++;
        }
    }
    
    protected function getPagesLinks($eventsData)
    {
        $links = array_get($eventsData, 'metadonnees.pagination.liens');
        $pagesLinks = [];
        foreach ($links as $link) {
            $pagesLinks[] = 'https://services.murmitoyen.com'.$link;
        }
        
        return $pagesLinks;
    }
    
    protected function loadEvents($date)
    {
        $url = 'http://services.murmitoyen.com/manivelle/evenements/'.$date;
        return $this->loadJSON($url);
    }
    
    protected function loadEvent($id)
    {
        $url = 'https://services.murmitoyen.com/manivelle/evenement/'.$id;
        $json = $this->loadJSON($url);
        return $json['donnees'];
    }
    
    protected function loadJSON($url)
    {
        $content = file_get_contents($url);
        $data = json_decode($content, true);
        return $data;
    }
    
    protected function getHandleFromData($data)
    {
        return 'murmitoyen_'.array_get($data, 'id', '');
    }
    
    protected function getFieldsFromData($data)
    {
        $description = array_get($data, 'base.0.description', '');
        $description = $this->sanitize($description);
        
        $fields = [
            'title' => array_get($data, 'base.0.titre', ''),
            'link' => 'http://murmitoyen.com/detail/'.array_get($data, 'base.0.id', ''),
            'description' => $description,
            'date' => [
                'start' => array_get($data, 'base.0.date', '').' '.array_get($data, 'base.0.heure_debut', ''),
                'end' => array_get($data, 'base.0.date', '').' '.array_get($data, 'base.0.heure_fin', '')
            ],
            'venue' => [
                'id' => array_get($data, 'lieu.0.id_lieu', ''),
                'name' => array_get($data, 'lieu.0.lieu_nom', ''),
                'address' => array_get($data, 'lieu.0.adresse', ''),
                'city' => array_get($data, 'lieu.0.ville', ''),
                'postalcode' => array_get($data, 'lieu.0.postalcode', ''),
                'position' => [
                    'latitude' => array_get($data, 'lieu.0.latitude', ''),
                    'longitude' => array_get($data, 'lieu.0.longitude', '')
                ]
            ]
        ];
        
        $updatedDate = Carbon::parse(array_get($data, 'base.0.date_modification'));
        $fields['updated_at'] = $updatedDate->toDateTimeString();
        
        //Groupe
        $groupId = array_get($data, 'groupes.0.id_groupe', '');
        $groupName = array_get($data, 'groupes.0.groupe_nom', '');
        if (!empty($groupId)) {
            $fields['group'] = [
                'id' => $groupId,
                'name' => $groupName
            ];
        }
        
        //Catégorie principale
        $categoryId = array_get($data, 'categories.0.id_categorie', '');
        $categoryName = array_get($data, 'categories.0.categorie_nom', '');
        if (!empty($categoryId)) {
            $fields['category'] = [
                'id' => $categoryId,
                'name' => $categoryName
            ];
        }
        
        //Sous catégories
        $subcategories = array_get($data, 'souscategories.0', []);
        foreach ($subcategories as $category) {
            $fields['subcategories'][] = [
                'id' => array_get($category, 'id_categorie', ''),
                'name' => array_get($category, 'categorie_nom', '')
            ];
        }
        
        //Image
        $picture = array_get($data, 'base.0.image', null);
        if (!empty($picture)) {
            $fields['picture'] = $picture;
            $fields['last_picture_filename'] = basename($picture);
        }
        
        if (!isset($picture)) {
            return false;
        }
        
        return $fields;
    }
    
    protected function distance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
    {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
        cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $earthRadius;
    }
    
    protected function sanitize($text)
    {
        return html_entity_decode(strip_tags(preg_replace('/\<br\s+?\/?\>/', "\n", $text)), ENT_QUOTES, 'utf-8');
    }
}
