<?php

namespace Manivelle\Banq\Sources\Jobs;

use Panneau;
use Panneau\Exceptions\ResourceNotFoundException;

use Manivelle\Sources\Job;
use Manivelle\Sources\Traits\ValidateImage;

use Manivelle\Models\Bubble;
use Manivelle\Models\Channel;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException;
use Exception;
use Log;
use Illuminate\Support\Str;

use Imagick;

abstract class SyncBanqApiJob extends Job
{
    use ValidateImage;
    
    public $requestOptions = [
        'type' => 'Livres',
        'page' => 1,
        'nbr' => 200,
        'texte' => 'canada'
    ];
    
    protected function syncPages()
    {
        $page = 1;
        $totalPage = -1;
        $count = 0;
        do {
            $response = $this->request([
                'page' => $page
            ]);
            
            if ($totalPage === -1 && !$response) {
                throw new Exception('No response');
            }
            
            if ($totalPage === -1) {
                $totalPage = $response['nbrPages'];
                $count = array_get($response, 'facettes.0.valeurs.0.nbr');
            }
            
            $isLastPage = $page === $totalPage ? true:false;
            $this->dispatch($this->createPageJob($page, $isLastPage));
            
            $page++;
        } while ($page <= $totalPage);
        
        return [
            'total_page' => $totalPage,
            'count' => $count
        ];
    }
    
    protected function syncPage($page)
    {
        $response = $this->request([
            'page' => $page
        ]);
        
        if (!$response) {
            throw new Exception('No response for page '.$this->page.'.');
        }
        
        //Get items
        $items = [];
        foreach ($response['resultats'] as $item) {
            $imagette = array_get($item, 'imagette', '');
            
            $isbn = array_get($item, 'isbn', '');
            if ($imagette === 'http://iris.banq.qc.ca/Portal3/IMG/MAT/mbook_f.gif' && !empty($isbn)) {
                $imagette = 'https://iris.banq.qc.ca/ElectreConnect/aspect.aspx?aspect=cover&isbn='.$isbn;
                array_set($item, 'imagette', $imagette);
            }
            
            if ($imagette && $imagette !== 'http://iris.banq.qc.ca/Portal3/IMG/MAT/mbook_f.gif') {
                $items[] = $item;
            }
        }
        
        $count = sizeof($items);
        for ($i = 0; $i < $count; $i++) {
            $item = $items[$i];
            $isLastItem = $this->isLastPage && $i === $count-1 ? true:false;
            $this->dispatch($this->createItemJob($item, $isLastItem));
        }
        
        $allCount = sizeof($response['resultats']);
        return [
            'count' => $allCount,
            'skipped' => $allCount - $count
        ];
    }
    
    abstract protected function createPageJob($item, $isLastPage = false);
    abstract protected function createItemJob($item, $isLast = false);
    
    protected function request($opts = [])
    {
        $opts = array_merge($this->requestOptions, $opts);
        
        $query = [
            'page' => $opts['page'],
            'nbr' => $opts['nbr'],
            'texte' => $opts['texte'],
            'filtres' => array_merge([
                [
                    'nom' => 'type_document_f',
                    'valeur' => $opts['type']
                ]
            ], array_get($opts, 'filters', []))
        ];
        
        try {
            $client = new HttpClient();
            $response = $client->request('GET', 'http://api.banq.qc.ca/valorisation/api/vitrine/recherche', [
                'query' => [
                    'json' => json_encode($query)
                ]
            ]);
        } catch (RequestException $e) {
            return null;
        } catch (Exception $e) {
            return null;
        }
        
        if ($response->getStatusCode() !== 200) {
            return null;
        }
        
        return json_decode($response->getBody(), true);
    }
    
    protected function requestNotice($handle, $opts = [])
    {
        $opts = array_merge([], $opts);
        
        $query = [
            'handle' => $handle
        ];
        
        try {
            $client = new HttpClient();
            $response = $client->request('GET', 'http://collections.banq.qc.ca/service-notice', [
                'query' => $query
            ]);
        } catch (RequestException $e) {
            return null;
        } catch (Exception $e) {
            return null;
        }
        
        if ($response->getStatusCode() !== 200) {
            return null;
        }
        
        return json_decode($response->getBody(), true);
    }
    
    protected function getThumbnailFromPDF($url)
    {
        try {
            $tmpPDFName = tempnam(sys_get_temp_dir(), 'banq_pdf').'.pdf';
            $tmpImageName = tempnam(sys_get_temp_dir(), 'banq_img').'.jpg';
            $client = new HttpClient();

            $client->get($url, [
                'save_to' => $tmpPDFName,
                'allow_redirects' => true
            ]);
            
            $im = new Imagick($tmpPDFName.'[0]');
            $im->setImageFormat('jpg');
            $im->writeImage($tmpImageName);
            
            return $tmpImageName;
        } catch (Exception $e) {
            return null;
        }
    }
    
    protected function getCategoriesFromStrings($strings)
    {
        $strings = (array)$strings;
        $categories = [];
        foreach ($strings as $string) {
            $categories[] = [
                'id' => Str::slug($string),
                'name' => $string
            ];
        }
        
        return $categories;
    }
    
    protected function getAuthorsFromStrings($names)
    {
        $names = (array)$names;
        $authors = [];
        foreach ($names as $name) {
            $nameParts = explode(',', $name);
            $date = array_get($nameParts, '2', '');
            $dates = explode('-', $date);
            $author = [
                'firstname' => trim(array_get($nameParts, '1', '')),
                'lastname' => trim(array_get($nameParts, '0', '')),
                'birth_year' => trim(array_get($dates, '0', '')),
                'death_year' => trim(array_get($dates, '1', ''))
            ];
            $authors[] = $author;
        }
        
        return $authors;
    }
}
