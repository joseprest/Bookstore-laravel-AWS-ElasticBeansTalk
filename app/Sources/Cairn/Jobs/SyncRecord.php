<?php

namespace Manivelle\Sources\Cairn\Jobs;

use Manivelle\Support\Str;
use Manivelle\Models\Bubble;
use GuzzleHttp\Client as HttpClient;

class SyncRecord extends SyncCairn
{
    public $id;
    public $isLast;

    protected $shouldNeverSkip = false;
    protected $shouldNeverFinish = false;
    protected $book_data = null;

    public function __construct($id, $isLast = false)
    {
        $this->id = $id;
        $this->isLast = $isLast;
    }

    public function sync()
    {
        $response = $this->request([
            'verb' => 'GetRecord',
            'metadataPrefix' => 'onix_dc',
            'identifier' => $this->id
        ]);

        $record = $response->GetRecord->record;

        $handle = $this->getHandleFromRecord($record);
        $fields = $this->getFieldsFromRecord($record);

        $bubble = $this->createBubble($handle, $fields);
        if ($bubble) {
            $this->addBubbleToChannels($bubble);
        }
    }

    public function getSourceJobKey()
    {
        $key = parent::getSourceJobKey();
        return $key.'_'.Str::slug($this->id);
    }
}
