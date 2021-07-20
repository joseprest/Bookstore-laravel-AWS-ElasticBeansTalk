<?php

namespace Manivelle\Sources\Cairn\Jobs;

use Manivelle\Support\Str;
use GuzzleHttp\Client as HttpClient;

class SyncPage extends SyncCairn
{
    public $set;
    public $resumptionToken;
    public $isLastPage = false;
    public $dispatchNext;

    protected $shouldNeverSkip = true;

    public function __construct($set = 'o:86', $resumptionToken = null, $isLastPage = false)
    {
        $this->set = $set;
        $this->resumptionToken = $resumptionToken;
        $this->isLastPage = $isLastPage;
    }

    public function sync()
    {
        $query = [];
        if ($this->resumptionToken) {
            $query = [
                'verb' => 'ListRecords',
                'resumptionToken' => $this->resumptionToken,
            ];
        } else {
            $query = [
                'verb' => 'ListRecords',
                'metadataPrefix' => 'oai_dc',
                'set' => $this->set,
            ];
        }

        $response = $this->request($query);

        $count = sizeof($response->ListRecords->record);
        $i = 0;
        foreach ($response->ListRecords->record as $record) {
            //$data = $this->getDataFromRecord($record);
            $id = (string)$record->header->identifier;
            $isLast = $this->isLastPage && $count-1 === $i;
            $job = new SyncRecord($id, $isLast);
            $this->dispatch($job);
            $i++;
        }

        // Next page
        if (isset($response->ListRecords->resumptionToken)) {
            $nextToken = (string)$response->ListRecords->resumptionToken;
            $count = (int)$response->ListRecords->resumptionToken['completeListSize'];
            $cursor = (int)$response->ListRecords->resumptionToken['cursor'];
            $isLastPage = ($cursor + $this->resultsPerPage) >= $count;
            $job = new SyncPage($this->set, $nextToken, $isLastPage);
            $this->dispatch($job);
        }
    }

    protected function getDataFromRecord($record)
    {
        $id = (string)$record->header->identifier;
        $dc = $record->metadata->children('http://www.openarchives.org/OAI/2.0/oai_dc/')->dc;
        $children = $dc->children('http://purl.org/dc/elements/1.1/');

        $link = null;
        $isbn = null;
        foreach ($children->identifier as $identifier) {
            if (preg_match('/^[0-9]+$/', (string)$identifier)) {
                $isbn = (string)$identifier;
            } elseif (filter_var((string)$identifier, FILTER_VALIDATE_URL)) {
                $link = (string)$identifier;
            }
        }

        return [
            'id' => $id,
            'link' => $link,
            'isbn' => $isbn,
            'title' => isset($children->title) ? (string)$children->title : null,
            'description' => isset($children->description) ? (string)$children->description : null,
            'author' => $this->getAuthorFromCreator(isset($children->creator) ? (string)$children->creator : null),
            'publisher' => isset($children->publisher) ? (string)$children->publisher : null,
            'date' => isset($children->date) ? (int)$children->date : null,
            'rights' => isset($children->rights) ? (string)$children->rights : null
        ];
    }

    protected function getAuthorFromCreator($creator)
    {
        $parts = explode(',', $creator, 2);
        if (sizeof($parts) === 2) {
            return [
                'firstname' => trim($parts[1]),
                'lastname' => trim($parts[0])
            ];
        }

        return [
            'name' => $creator
        ];
    }

    public function getSourceJobKey()
    {
        $key = parent::getSourceJobKey();
        return $key.'_'.$this->set.'_p_'.($this->resumptionToken ? $this->resumptionToken : 'first');
    }
}
