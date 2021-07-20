<?php

namespace Manivelle\Sources\MurMitoyen\Jobs;

use Panneau;
use Exception;
use Panneau\Exceptions\ResourceNotFoundException;

use Manivelle\Models\Bubble;
use Manivelle\Models\Channel;

use Carbon\Carbon;
use Illuminate\Log\Writer;
use Manivelle\Sources\Job;
use GuzzleHttp\Client as HttpClient;

class SyncMurMitoyen extends Job
{
    protected $shouldNeverSkip = true;

    public function sync()
    {
        $now = Carbon::now();
        $eventsData = $this->loadEvents($now->toDateString());
        $pagesLink = $this->getPagesLinks($eventsData);

        $pagesCount = sizeof($pagesLink);
        for ($i = 0; $i < $pagesCount; $i++) {
            $pageLink = $pagesLink[$i];
            $isLastPage = $i === $pagesCount-1 ? true:false;
            $job = new SyncEventsPage($pageLink, $isLastPage);
            $this->dispatch($job);
        }
    }

    public function getSourceJobKey()
    {
        return 'murmitoyen';
    }

    protected function getPagesLinks($eventsData)
    {
        $endpoint = config('manivelle.sources.murmitoyen.api_endpoint');
        $links = array_get($eventsData, 'metadonnees.pagination.liens');
        $pagesLinks = [];
        foreach ($links as $link) {
            $pagesLinks[] = rtrim($endpoint, '/').$link;
        }

        return $pagesLinks;
    }

    protected function loadEvents($date)
    {
        $endpoint = config('manivelle.sources.murmitoyen.api_endpoint');
        $url = rtrim($endpoint, '/').'/manivelle/evenements/'.$date;
        return $this->loadJSON($url);
    }

    protected function loadEvent($id)
    {
        $endpoint = config('manivelle.sources.murmitoyen.api_endpoint');
        $url = rtrim($endpoint, '/').'/manivelle/evenement/'.$id;
        $json = $this->loadJSON($url);
        return $json && is_array($json) ? array_get($json, 'donnees', []):[];
    }

    protected function loadJSON($url)
    {
        $client = new HttpClient();
        $response = $client->request('GET', $url);

        $status = $response->getStatusCode();
        if ($status !== 200) {
            throw new \Exception('[Source murmitoyen] Status code '.$status.' for '.$url);
        }

        $data = json_decode($response->getBody(), true);

        /*$content = file_get_contents($url);
        $data = json_decode($content, true);*/
        return $data;
    }

    protected function getHandleFromData($data)
    {
        return 'murmitoyen_'.array_get($data, 'id', array_get($data, 'base.0.id', ''));
    }

    protected function getFieldsFromData($data)
    {
        $description = array_get($data, 'base.0.description', '');
        $description = $this->sanitize($description);

        $date = array_get($data, 'base.0.date', '');
        $timeStart = array_get($data, 'base.0.heure_debut', '');
        $timeEnd = array_get($data, 'base.0.heure_fin', '');
        $dateStart = [];
        $dateEnd = [];
        if (!empty($date)) {
            $dateStart[] = $date;
        }
        if (!empty($timeStart)) {
            $dateStart[] = $timeStart;
        }
        if (!empty($date) && !empty($timeEnd)) {
            $dateEnd[] = $date;
            $dateEnd[] = $timeEnd;
        }
        $room = array_get($data, 'lieu.0.salle', '');
        $fields = [
            'title' => array_get($data, 'base.0.titre', ''),
            'link' => 'http://murmitoyen.com/detail/'.array_get($data, 'base.0.id', ''),
            'description' => $description,
            'date' => [
                'start' => sizeof($dateStart) ? implode(' ', $dateStart):null,
                'end' => sizeof($dateEnd) ? implode(' ', $dateEnd):null
            ],
            'room' => !empty($room) ? $room : null,
            'venue' => $this->getVenueFromData($data)
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
            if (is_array($category)) {
                $fields['subcategories'][] = [
                    'id' => array_get($category, 'id_categorie', ''),
                    'name' => array_get($category, 'categorie_nom', '')
                ];
            }
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

    protected function getVenueFromData($data)
    {
        $location = array_get($data, 'lieu.0', null);

        if (empty($location)) {
            return null;
        }

        $province = array_get($data, 'lieu.0.province', '');
        $latitude = array_get($data, 'lieu.0.latitude', '');
        $longitude = array_get($data, 'lieu.0.longitude', '');

        $location = [
            'id' => array_get($data, 'lieu.0.id_lieu', ''),
            'name' => array_get($data, 'lieu.0.lieu_nom', ''),
            'address' => array_get($data, 'lieu.0.adresse', ''),
            'city' => array_get($data, 'lieu.0.ville', ''),
            'postalcode' => array_get($data, 'lieu.0.code_postal', ''),
            'region' => $province === 'QC' ? 'Québec':$province,
            'country' => array_get($data, 'lieu.0.pays', ''),
            'position' => $latitude !== null && $longitude !== null ? [
                'latitude' => array_get($data, 'lieu.0.latitude', ''),
                'longitude' => array_get($data, 'lieu.0.longitude', '')
            ]:null
        ];

        $locationNotNull = [];
        foreach ($location as $key => $value) {
            if ($value !== null) {
                $locationNotNull[$key] = is_string($value) ? trim($value) : $value;
            }
        }

        return $locationNotNull;
    }

    protected function bubbleHasChanged($bubble, $fields)
    {
        $currentUpdatedDate = $fields['updated_at'] ? Carbon::parse($fields['updated_at']):null;
        $bubbleUpdateDate = $bubble->fields->updated_at ? Carbon::parse($bubble->fields->updated_at):null;
        if ((!$currentUpdatedDate && $bubbleUpdateDate) ||
            (!$bubbleUpdateDate && $currentUpdatedDate) ||
            $currentUpdatedDate->gt($bubbleUpdateDate)
        ) {
            return true;
        }

        return false;
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
