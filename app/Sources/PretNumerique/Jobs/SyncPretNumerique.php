<?php

namespace Manivelle\Sources\PretNumerique\Jobs;

use Manivelle\Support\SyncJob;
use Manivelle\Jobs\CreateImagesJob;
use Panneau\Exceptions\ResourceNotFoundException;
use Carbon\Carbon;
use Illuminate\Log\Writer;
use Manivelle\Sources\Job;
use Illuminate\Support\Str;

class SyncPretNumerique extends Job
{
    protected $shouldNeverSkip = true;
    protected $libraries = [];

    public function __construct($libraries)
    {
        $this->libraries = $libraries;
    }

    public function sync()
    {
        $lastSync = $this->source ? $this->source->getLastSync() : null;
        $lastSourceUpdate = $this->source ? $this->source->updated_at->copy() : null;
        $lastSyncDate = $lastSync ? $lastSync->created_at->copy() : null;
        $useDateFrom = config('manivelle.sources.pretnumerique.query_from_last_update');
        $dateFrom = $useDateFrom && $lastSourceUpdate && $lastSyncDate && $lastSyncDate->gte($lastSourceUpdate) ?
            $lastSyncDate->subDays(4) : null;
        foreach ($this->libraries as $library) {
            $job = new SyncLibrary($library, $dateFrom);
            $this->dispatch($job);
        }
    }

    public function getSourceJobKey()
    {
        return 'pretnumerique';
    }

    protected function shouldExclude($handle, $fields)
    {
        // Check if category is excluded from sync
        $excludedCategories = config('manivelle.sources.pretnumerique.excluded_categories');
        $categories = array_map(function ($category) {
            return $category['id'];
        }, array_get($fields, 'categories', []));
        foreach ($categories as $category) {
            if (in_array($category, $excludedCategories)) {
                return true;
            }
        }

        return false;
    }

    protected function updateDataFromBubble($bubble, $data)
    {
        $libraries = array_pluck($bubble->fields->libraries, 'key');
        if ($this->library && !in_array($this->library, $libraries)) {
            $libraries[] = $this->library;
            $data['fields']['libraries'] = $libraries;
        }

        //Update ids
        $newId = array_get($data, 'fields.pretnumerique_ids.0');
        $currentIds = $bubble->fields->pretnumerique_ids;
        $newIds = [];
        $found = false;
        foreach ($currentIds as $currentId) {
            if ($currentId->library === $newId['library']) {
                if ($currentId->id !== $newId['id'] && !$found) {
                    $newIds[] = $newId;
                }
                $found = true;
            } else {
                $newIds[] = [
                    'id' => $currentId->id,
                    'library' => $currentId->library
                ];
            }
        }
        if (!$found) {
            $newIds[] = $newId;
        }
        $data['fields']['pretnumerique_ids'] = $newIds;

        return $data;
    }

    protected function bubbleHasChanged($bubble, $fields)
    {
        return true;

        //Check if it's an old handle or missing ISBN or missing IDS
        if (preg_match('/^book\_/', $bubble->handle) ||
            !isset($bubble->fields->isbn) || empty($bubble->fields->isbn) ||
            !sizeof($bubble->fields->pretnumerique_ids) ||
            sizeof($bubble->fields->pretnumerique_ids)  !== sizeof($fields['pretnumerique_ids'])
        ) {
            return true;
        }

        //Check if the id for each library are okay
        foreach ($bubble->fields->pretnumerique_ids as $id) {
            if (empty($id->id)) {
                return true;
            }
            foreach ($fields['pretnumerique_ids'] as $currentId) {
                if ($currentId['library'] === $id->library && $currentId['id'] !== $id->id) {
                    return true;
                }
            }
        }

        //Check if the categories are the same
        $currentCategories = [];
        $nextCategories = [];
        foreach ($bubble->fields->categories as $category) {
            $currentCategories[] = $category->id;
        }
        foreach ($fields['categories'] as $category) {
            $nextCategories[] = $category['id'];
        }
        sort($currentCategories);
        sort($nextCategories);
        if (implode('_', $currentCategories) !== implode('_', $nextCategories)) {
            return true;
        }

        //Check the update date
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

    protected function getHandleFromData($data)
    {
        return 'pretnumerique_'.Str::slug(array_get($data, 'id'));
    }

    protected function getFieldsFromData($data, $library)
    {
        $fields = [
            'pretnumerique_id' => Str::slug(array_get($data, 'id', '')),
            'pretnumerique_ids' => [
                [
                    'id' => $this->getIdFromData($data),
                    'library' => $library
                ]
            ],
            'isbn' => $this->getIsbnFromData($data),
            'title' => array_get($data, 'title'),
            'summary' => array_get($data, 'summary'),
            //'author' => $this->getAuthorFromData($data),
            //'category' => $this->getCategoryFromData($data),
            'authors' => $this->getAuthorsFromData($data),
            'categories' => $this->getCategoriesFromData($data),
            'libraries' => [$library],
            'publisher' => array_get($data, 'publisher_name'),
            'language' => substr(array_get($data, 'languages.0', ''), 0, 2)
        ];

        $coverLarge = array_get($data, 'cover_large');
        $fields['cover_front'] = !empty($coverLarge) ? $coverLarge:array_get($data, 'cover');

        $now = Carbon::now();
        $createDate = Carbon::parse(array_get($data, 'media.0.issued_on', ''));
        $fields['date'] = $createDate->toDateTimeString();

        $updatedDate = Carbon::parse(array_get($data, 'updated_at', ''));
        $fields['updated_at'] = $updatedDate->year > 0 ? $updatedDate->toDateTimeString():$now->toDateTimeString();

        return $fields;
    }

    protected function getAuthorsFromData($data)
    {
        $authors = [];
        $contributors = array_get($data, 'contributors', []);
        foreach ($contributors as $contributor) {
            $authors[] = [
                'name' => $contributor['first_name'].' '.$contributor['last_name'],
                'firstname' => $contributor['first_name'],
                'lastname' => $contributor['last_name'],
                'id' => Str::slug($contributor['last_name'].' '.$contributor['first_name'])
            ];
        }

        return $authors;
    }

    protected function getAuthorFromData($data)
    {
        $contributors = array_get($data, 'contributors', []);
        foreach ($contributors as $contributor) {
            if ($contributor['nature'] === 'author') {
                return $contributor['first_name'].' '.$contributor['last_name'];
            }
        }

        return null;
    }

    protected function getCategoryFromData($data)
    {
        $categories = $this->getCategoriesFromData($data);
        return array_get($categories, '0.id');
    }

    protected function getCategoriesFromData($data)
    {
        $categories = [];
        $classificationsMatch = [];
        $classifications = array_get($data, 'classifications', []);
        foreach ($classifications as $classification) {
            $standard = array_get($classification, 'standard');
            $identifiers = array_get($classification, 'identifiers', []);

            //Load standard json file if it exists
            $matchPath = storage_path('sources/'.$standard.'.json');
            if (!isset($classificationsMatch[$standard]) && file_exists($matchPath)) {
                $classificationsMatch[$standard] = json_decode(file_get_contents($matchPath), true);
            }

            // Loop through identifiers, if it can't be found in the categories list, try
            // to find it in the matches list from other standards.
            foreach ($identifiers as $id) {
                $label = config('manivelle.sources.pretnumerique.categories.'.$id);

                if (empty($label)) {
                    $matchId = array_get($classificationsMatch, $standard.'.'.$id);
                    if (!empty($matchId)) {
                        $label = config('manivelle.sources.pretnumerique.categories.'.$matchId);
                        $id = $matchId;
                    }
                }

                if (!empty($label) && !isset($categories[$id])) {
                    $categories[$id] = [
                        'id' => $id,
                        'name' => $label
                    ];
                }
            }
        }

        return array_values($categories);
    }

    protected function getIsbnFromData($data)
    {
        $isbn = null;
        $medias = array_get($data, 'media', []);
        foreach ($medias as $media) {
            if ((empty($isbn) || $media['nature'] === 'epub') && $media['key_type'] === 'isbn13') {
                $isbn = $media['key'];
            }
        }

        return $isbn;
    }

    protected function getIdFromData($data)
    {
        $link = array_get($data, 'link');
        preg_match('/^http\:\/\/([^\.]+).pretnumerique\.ca\/resources\/([^\/]+)$/', $link, $matches);
        return array_get($matches, '2', null);
    }
}
