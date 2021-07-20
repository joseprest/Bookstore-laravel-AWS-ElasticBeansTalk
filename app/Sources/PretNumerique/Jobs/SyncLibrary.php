<?php

namespace Manivelle\Sources\PretNumerique\Jobs;

use Manivelle\Support\Str;
use GuzzleHttp\Client as HttpClient;

class SyncLibrary extends SyncPretNumerique
{
    public $library;
    public $dateFrom;

    protected $shouldNeverSkip = true;

    public function __construct($library, $dateFrom = null)
    {
        $this->library = $library;
        $this->dateFrom = $dateFrom;
    }

    public function sync()
    {
        $url = 'http://'.$this->library.'.pretnumerique.ca/v1/resources.json';
        $query = [];
        if (!is_null($this->dateFrom)) {
            $query['start_at'] = $this->dateFrom->toDateString();
        }
        $client = new HttpClient();
        $response = $client->request('GET', $url, [
            'query' => $query
        ]);
        $totalPages = array_get($response->getHeader('x-total-pages'), '0', 0);

        for ($i = 0; $i < $totalPages; $i++) {
            $isLast = $i === ($totalPages-1) ? true:false;
            $job = new SyncPage(($i+1), $this->library, $this->dateFrom);
            $this->dispatch($job);
        }
    }

    public function getSourceJobKey()
    {
        $key = parent::getSourceJobKey();
        return $key.'_'.Str::slug($this->library);
    }
}
