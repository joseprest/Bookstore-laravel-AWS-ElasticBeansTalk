<?php

namespace Manivelle\Banq\Sources\Jobs;

use Manivelle\Models\Bubble;
use Manivelle\Models\Channel;
use Illuminate\Log\Writer;
use Carbon\Carbon;
use Illuminate\Support\Str;

class SyncBanqBooks extends SyncBanqApiJob
{

    public $requestOptions = [
        'type' => 'Livres',
        'page' => 1,
        'nbr' => 100,
        'texte' => ''
    ];
    
    protected $shouldNeverSkip = true;
    
    public function getSourceJobKey()
    {
        return 'banq_books';
    }
    
    public function sync()
    {
        $startYear = config('manivelle.banq.books.source_start_year');
        $endYear = Carbon::now()->year;
        for ($i = $startYear; $i <= $endYear; $i++) {
            $isLastYear = $i === $endYear ? true:false;
            $this->dispatch(new SyncBanqBooksYear($i, $isLastYear));
        }
    }
    
    protected function createItemJob($item, $isLast = false)
    {
        return new SyncBanqBooksItem($item, $isLast);
    }
    
    protected function createPageJob($page, $isLastPage = false)
    {
        $isLastPage = $this->isLastYear && $isLastPage ? true:false;
        return new SyncBanqBooksPage($this->year, $page, $isLastPage);
    }
    
    protected function bubbleHasChanged($bubble, $fields)
    {
        if ($bubble->fields->image) {
            $currentImage = $bubble->fields->image->name;
            if (!preg_match('/'.preg_quote($currentImage).'/', $fields['image'])) {
                return true;
            }
        }
        
        $fieldsToCheck = ['title', 'author', 'isbn', 'physical_description', 'genres'];
        foreach ($fieldsToCheck as $field) {
            $value = array_get($fields, $field);
            $currentValue = $bubble->fields->{$field};
            if ($currentValue !== $value) {
                return true;
            }
        }
        
        return false;
    }
    
    protected function getFieldsFromItem($item)
    {
        $fields = [];
        $fields['banq_id'] = array_get($item, 'id', '');
        $fields['isbn'] = array_get($item, 'isbn', '');
        $fields['title'] = array_get($item, 'titre', '');
        /*$fields['author'] = array_get($item, 'createurPrincipal', []);
        if (isset($fields['author']) && sizeof($fields['author'])) {
            $fields['author'] = $fields['author'][0];
        } else {
            $fields['author'] = array_get($item, 'auteur', '');
        }*/
        $fields['authors'] = $this->getAuthorsFromStrings(array_get($item, 'createurAffichageList', []));
        $fields['date'] = isset($item['annee']) ? ($item['annee'].'-01-01'):'';
        $fields['physical_description'] = array_get($item, 'descriptionMateriel', '');
        $fields['pages'] = (int)trim(array_get($item, 'nombrePage', '0'));
        $fields['awards'] = array_get($item, 'prixRecuAffichageList', []);
        $fields['quebec_creator'] = array_get($item, 'createur_quebecois', '');
        
        $fields['collections'] = $this->getCategoriesFromStrings(array_get($item, 'collectionAffichageList', []));
        $fields['nationals_collections'] = $this->getCategoriesFromStrings(array_get($item, 'litteratureNationnaleAffichageList', []));
        $fields['origin'] = $this->getCategoriesFromStrings(array_get($item, 'provenance', []));
        $fields['characters'] = $this->getCategoriesFromStrings(array_get($item, 'personnages', []));
        $fields['subjects'] = $this->getCategoriesFromStrings(array_get($item, 'sujet', []));
        $fields['locations'] = $this->getCategoriesFromStrings(array_get($item, 'lieuDuRecit', []));
        $fields['genres'] = $this->getCategoriesFromStrings(array_get($item, 'genreLitteraire', []));
        
        $id = array_get($item, 'id', '');
        if (!empty($id)) {
            $fields['link'] = 'http://iris.banq.qc.ca/iris.aspx?fn=ViewNotice&Style=Portal3&q='.$id;
        }
        
        $url = array_get($item, 'url');
        if (!empty($url) && preg_match('/ark\:\/([0-9]+)\/([0-9]+)$/', $url, $matches)) {
            $handle = $matches[1].'/'.$matches[2];
            $notice = $this->requestNotice($handle);
            if ($notice) {
                $fields['publisher'] = array_get($notice, 'editeur.0', '');
                $medias = array_get($notice, 'bitstreams.liste', []);
                foreach ($medias as $media) {
                    $url = array_get($media, 'url');
                    if (!empty($url) && preg_match('/\.pdf$/', $url)) {
                        $image = $this->getThumbnailFromPDF($url);
                        if ($image) {
                            $fields['cover_front'] = $image;
                        }
                    }
                }
            }
        }
        
        $imagette = array_get($item, 'imagette');
        if (!isset($fields['cover_front']) &&
            !empty($imagette) &&
            (
                preg_match('/retrieve\/([0-9]+)$/', $imagette) ||
                preg_match('/isbn\=/', $imagette)
            )
        ) {
            $fields['cover_front'] = $imagette;
        }
        
        $notEmptyFields = [];
        foreach ($fields as $key => $value) {
            if (!empty($value)) {
                $notEmptyFields[$key] = $value;
            }
        }
        
        $fields = $notEmptyFields;
        
        if (!isset($fields['cover_front'])) {
            return null;
        } else {
            $fields['cover_front'] = preg_replace('/^https\:\/\/iris\./', 'http://iris.', $fields['cover_front']);
        }
        
        return $fields;
    }
    
    protected function getHandleFromItem($item)
    {
        $isbn = array_get($item, 'isbn', '-');
        $id = array_get($item, 'id', '-');
        $handle = 'banq_books_'.$id.'_'.$isbn;
        
        return $handle;
    }
}
