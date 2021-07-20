<?php

namespace Manivelle\Banq\Sources\Jobs;

use Panneau;
use Panneau\Exceptions\ResourceNotFoundException;

use Manivelle\Support\SyncJob;

use Manivelle\Models\Bubble;
use Manivelle\Models\Channel;
use Illuminate\Log\Writer;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException;
use Exception;

use Imagick;

class SyncBanqQuizzPage extends SyncBanqQuizz
{
    public $page;
    public $isLastPage = false;

    protected $shouldNeverSkip = true;

    public function __construct($page, $isLastPage = false)
    {
        $this->page = $page;
        $this->isLastPage = $isLastPage;
    }

    public function getSourceJobKey()
    {
        $key = parent::getSourceJobKey();
        return $key.'_page_'.$this->page;
    }

    public function sync()
    {
        $response = $this->request($this->page);

        if (!$response || !isset($response['items'])) {
            throw new Exception('No items found');
        }

        //Get items
        $items = [];
        foreach ($response['items'] as $item) {
            if (!isset($item['reponse_vitrine'])) {
                continue;
            }

            $items[] = $item;
        }

        //Dispatch items
        $count = sizeof($items);
        for ($i = 0; $i < $count; $i++) {
            $item = $items[$i];
            $isLastItem = $this->isLastPage && $i === $count-1 ? true:false;
            $this->dispatch(new SyncBanqQuizzQuestion($item, $isLastItem));
        }

        if ($this->isLastPage && !$count) {
            $this->finish();
        }
    }
}
