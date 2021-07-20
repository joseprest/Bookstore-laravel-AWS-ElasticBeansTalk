<?php

namespace Manivelle\Sources\PretNumerique\Jobs;

use Manivelle\Support\Str;
use Manivelle\Models\Bubble;
use GuzzleHttp\Client as HttpClient;

class SyncBook extends SyncPretNumerique
{
    public $id;
    public $library;
    public $isLast;

    protected $shouldNeverSkip = false;
    protected $shouldNeverFinish = false;
    protected $book_data = null;

    public function __construct($id, $library, $isLast = false)
    {
        $this->id = is_array($id) ? $this->getIdFromData($id) : $id;
        $this->book_data = is_array($id) ? $id : null;
        $this->library = $library;
        $this->isLast = $isLast;
    }

    public function sync()
    {
        $data = $this->getBookData();
        $handle = $this->getHandleFromData($data);
        $fields = $this->getFieldsFromData($data, $this->library);

        //Check if bubble should be excluded
        if ($this->shouldExclude($handle, $fields)) {
            $key = $this->getSourceJobKey();
            $this->output('<info>Job excluded:</info> '.$key);
            $bubble = $this->getBubbleFromHandle($handle);
            if ($bubble) {
                $bubble->delete();
                $this->output('<info>Bubble deleted:</info> #'.$bubble->id.' '.$handle);
            }
            return;
        }

        $bubble = $this->createBubble($handle, $fields);
        if ($bubble) {
            $this->addBubbleToChannels($bubble);
        }
    }

    public function getBookData()
    {
        if (!$this->book_data) {
            $this->book_data = $this->requestBookData();
        }

        return $this->book_data;
    }

    public function setBookData($data)
    {
        $this->book_data = $data;
    }

    public function requestBookData()
    {
        $url = 'http://'.$this->library.'.pretnumerique.ca/v1/resources/'.$this->id.'.json';
        $client = new HttpClient();
        $response = $client->request('GET', $url);

        $status = $response->getStatusCode();
        if ($status !== 200) {
            throw new \Exception('[Source pretnumerique] Status code '.$status.' for '.$url);
        }

        $data = json_decode($response->getBody(), true);

        return $data;
    }

    public function getSourceJobKey()
    {
        $key = parent::getSourceJobKey();
        return $key.'_'.Str::slug($this->library).'_'.Str::slug($this->id);
    }

    public function shouldSkip()
    {
        return parent::shouldSkip();

        $shouldSkip = parent::shouldSkip();
        if ($shouldSkip) {
            return true;
        }

        $data = $this->getBookData();
        $handle = $this->getHandleFromData($data);
        $current = Bubble::where('handle', $handle)->first();
        if (!$current) {
            return false;
        }

        $fields = $this->getFieldsFromData($data, $this->library);
        $shouldSkip = $this->bubbleHasChanged($current, $fields) ? false:true;
        return $shouldSkip;
    }

    public function getLibraries()
    {
        $fromDatabase = config('manivelle.sources.pretnumerique.libraries_from_database', false);
        $defaultLibraries = config('manivelle.sources.pretnumerique.libraries', []);
        return $fromDatabase && !is_null($this->source) && is_array($this->source->settings) ?
            array_get($this->source->settings, 'libraries', $defaultLibraries) : $defaultLibraries;
    }

    protected function shouldFinishAll()
    {
        $jobKeys = array_map(function ($library) {
            return 'finished_'.$library;
        }, $this->getLibraries());

        return $this->sourceSync->isJobsSynced($jobKeys);
    }

    public function finish()
    {
        $this->finishLibrary();

        if ($this->shouldFinishAll()) {
            parent::finish();
        }
    }

    protected function finishLibrary()
    {
        $this->sourceSync->addSyncedJob('finished_'.$this->library);
    }
}
