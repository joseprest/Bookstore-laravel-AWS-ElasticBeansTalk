<?php

namespace Manivelle\Sources\PretNumerique\Jobs;

use Manivelle\Support\Str;
use GuzzleHttp\Client as HttpClient;

class SyncPage extends SyncPretNumerique
{
    public $page;
    public $dateFrom;
    public $isLastPage = false;
    public $library;
    public $dispatchNext;

    protected $shouldNeverSkip = true;

    public function __construct($page, $library, $dateFrom = null, $isLastPage = false)
    {
        $this->page = $page;
        $this->library = $library;
        $this->dateFrom = $dateFrom;
        $this->isLastPage = $isLastPage;
    }

    public function sync()
    {
        $url = 'http://'.$this->library.'.pretnumerique.ca/v1/resources.json';
        $query = [
            'page' => $this->page
        ];
        if (!is_null($this->dateFrom)) {
            $query['start_at'] = $this->dateFrom->toDateString();
        }

        $client = new HttpClient();
        $response = $client->request('GET', $url, [
            'query' => $query
        ]);

        $status = $response->getStatusCode();
        if ($status !== 200) {
            throw new \Exception('[Source pretnumerique] Status code '.$status.' for '.$url);
        }

        $data = json_decode($response->getBody(), true);
        $resources = array_get($data, 'resources', []);
        $count = sizeof($resources);
        $i = 0;
        foreach ($resources as $resource) {
            $isLast = $this->isLastPage && $i === ($count-1) ? true:false;
            // $id = $this->getIdFromData($resource);
            $job = new SyncBook($resource, $this->library, $isLast);
            $job->setBookData($resource);
            $this->dispatch($job);
            $i++;
        }
    }

    public function getSourceJobKey()
    {
        $key = parent::getSourceJobKey();
        return $key.'_'.Str::slug($this->library).'_p_'.$this->page;
    }
}
