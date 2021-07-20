<?php

namespace Manivelle\Banq\Sources\Jobs;

use Manivelle\Models\Bubble;
use Manivelle\Models\Channel;
use Illuminate\Log\Writer;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException;
use Exception;
use Cache;
use Log;

use Manivelle\Support\Str;

use Manivelle\Banq\Photos\PhotoBubble;

class SyncBanqPhotos extends SyncBanqApiJob
{
    public $requestOptions = [
        'type' => 'Images',
        'page' => 1,
        'nbr' => 200,
        'texte' => 'Conrad'
    ];

    protected $shouldNeverSkip = true;

    protected $minimumImageSize = 400;

    public function getSourceJobKey()
    {
        return 'banq_photos';
    }

    public function sync()
    {
        if (!config('manivelle.banq.photos.disable_source')) {
            $result = $this->syncPages();
        }
    }

    protected function createItemJob($item, $isLast = false)
    {
        return new SyncBanqPhotosItem($item, $isLast);
    }

    protected function createPageJob($page, $isLastPage = false)
    {
        return new SyncBanqPhotosPage($page, $isLastPage);
    }

    protected function getFieldsFromItem($item)
    {
        $fields = [];
        $fields['banq_id'] = array_get($item, 'id', '');
        $fields['title'] = array_get($item, 'titreIcono', '');
        $fields['description'] = array_get($item, 'porteeContenu', array_get($item, 'depouillementContenu', ''));
        //$fields['author'] = array_get($item, 'auteur');
        $fields['authors'] = $this->getAuthorsFromStrings(array_get($item, 'createurAffichageList', []));
        $fields['subjects'] = $this->getCategoriesFromStrings(array_get($item, 'sujet', []));
        $fields['location'] = $this->getLocationFromName(array_get($item, 'lieuRepresente', array_get($item, 'lieu', '')));
        $fields['date'] = isset($item['dateIcono']) && !empty($item['dateIcono']) ? ($item['dateIcono'].'-01-01'):'';
        $fields['date_text'] = array_get($item, 'dateAffichage');
        $fields['physical_description'] = array_get($item, 'descriptionMateriel', '');

        $collection = array_get($item, 'collectionPublication');
        $subCollection = array_get($item, 'sousCollection');
        $collections = [];
        if (!empty($collection)) {
            $collections[] = $collection;
        }
        if (!empty($subCollection)) {
            $collections[] = $subCollection;
        }
        $fields['collections'] = $this->getCategoriesFromStrings($collections);

        $id = array_get($item, 'id', '');
        $url = array_get($item, 'url');
        if (!empty($url)) {
            $fields['link'] = $url;
        } elseif (!empty($id)) {
            $fields['link'] = 'http://iris.banq.qc.ca/iris.aspx?fn=ViewNotice&Style=Portal3&q='.$id;
        }

        if (!empty($url) && preg_match('/ark\:\/([0-9]+)\/([0-9]+)$/', $url, $matches)) {
            $handle = $matches[1].'/'.$matches[2];
            $notice = $this->requestNotice($handle);
            if ($notice) {
                $fields['publisher'] = array_get($notice, 'editeur.0', '');
            }
        }

        $fields['image'] = $this->getImageFromItem($item);

        $notEmptyFields = [];
        foreach ($fields as $key => $value) {
            if (!empty($value)) {
                $notEmptyFields[$key] = $value;
            }
        }

        if (!isset($fields['image'])) {
            return null;
        }

        return $fields;
    }

    protected function getHandleFromItem($item)
    {
        $id = array_get($item, 'id', '-');
        $handle = 'banq_photos_'.$id;
        return $handle;
    }

    protected function getLocationFromName($locationName)
    {
        if (empty($locationName)) {
            return null;
        }

        $locationName = PhotoBubble::normalizeLocation($locationName);
        $slugLocation = Str::slug($locationName);
        $locations =  config('manivelle.banq.photos.locations');
        $foundLocation = [];
        foreach ($locations as $pattern => $location) {
            if (preg_match('/^\/(.*?)\/$/', $pattern) && preg_match($pattern, $locationName)) {
                $foundLocation = $location;
            } elseif (Str::slug($pattern) === $slugLocation) {
                $foundLocation = $location;
            }
        }

        if (!sizeof($foundLocation)) {
            $geocode = Cache::remember('banq_gmap_geocoding_'.$slugLocation, 60*24*7, function () use ($locationName) {
                return $this->geocodeLocation($locationName);
            });
            if ($geocode) {
                if (isset($geocode['position'])) {
                    $foundLocation['position'] = $geocode['position'];
                }
                if (isset($geocode['address'])) {
                    $foundLocation['address'] = $geocode['address'];
                }
                if (isset($geocode['postalcode'])) {
                    $foundLocation['postalcode'] = $geocode['postalcode'];
                }
                if (isset($geocode['city'])) {
                    $foundLocation['city'] = $geocode['city'];
                }
                if (isset($geocode['region'])) {
                    $foundLocation['region'] = $geocode['region'];
                }
            }
        }

        return array_merge([
            'name' => $locationName,
            'address' => $locationName,
            'id' => $slugLocation
        ], $foundLocation);
    }

    protected function geocodeLocation($name)
    {
        $url = 'https://maps.googleapis.com/maps/api/geocode/json';
        $query = [
            'address' => $name.', Canada',
            'key' => config('services.google.key')
        ];

        try {
            $client = new HttpClient();
            $response = $client->request('GET', $url, [
                'query' => $query
            ]);
        } catch (RequestException $e) {
            Log::error($e);
            return null;
        } catch (Exception $e) {
            Log::error($e);
            return null;
        }

        if ($response->getStatusCode() !== 200) {
            Log::info('[Source BanqPhotos] Geocode response: '.$response->getStatusCode());
            return null;
        }

        $data = json_decode($response->getBody(), true);
        $components = array_get($data, 'results.0.address_components', []);
        $position = array_get($data, 'results.0.geometry.location', null);
        $postalcode = array_first($components, function ($key, $value) {
            return in_array('postal_code', array_get($value, 'types', []));
        });
        $address = array_first($components, function ($key, $value) {
            return in_array('route', array_get($value, 'types', []));
        });
        $city = array_first($components, function ($key, $value) {
            return in_array('locality', array_get($value, 'types', []));
        });
        $region = array_first($components, function ($key, $value) {
            return in_array('administrative_area_level_1', array_get($value, 'types', []));
        });
        $country = array_first($components, function ($key, $value) {
            return in_array('country', array_get($value, 'types', []));
        });

        $geocode = [];
        if ($position) {
            $geocode['position'] = [
                'latitude' => $position['lat'],
                'longitude' => $position['lng']
            ];
        }

        if ($city) {
            $geocode['city'] = $city['long_name'];
        }
        if ($postalcode) {
            $geocode['postalcode'] = $postalcode['long_name'];
        }
        if ($address) {
            $geocode['address'] = $address['long_name'];
        }

        if ($region) {
            $geocode['region'] = $region['long_name'];
        }

        if ($country) {
            $geocode['country'] = $country['long_name'];
        }

        $geocode = sizeof($geocode) ? $geocode:null;

        Log::info('[Source BanqPhotos] Geocoded: '.$name, $geocode ? $geocode:[]);

        return $geocode;
    }
}
