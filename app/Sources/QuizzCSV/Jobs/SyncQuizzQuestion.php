<?php

namespace Manivelle\Sources\QuizzCSV\Jobs;

use Manivelle\Support\SyncJob;
use Manivelle\Jobs\CreateImagesJob;

use Panneau;
use DB;
use Exception;
use Panneau\Exceptions\ResourceNotFoundException;

use Carbon\Carbon;
use Illuminate\Log\Writer;

use Manivelle\Sources\Job;

use Illuminate\Support\Str;

class SyncQuizzQuestion extends SyncQuizzCSV
{
    protected $shouldNeverSkip = true;

    public $question;
    public $isLast = false;

    public function __construct($question, $isLast = false)
    {
        $this->question = $question;
        $this->isLast = $isLast;
    }

    public function sync()
    {
        $handle = $this->getHandleFromRecord($this->question);
        $fields = $this->getFieldsFromRecord($this->question);

        $bubble = $this->createBubble($handle, $fields);
        if ($bubble) {
            $this->addBubbleToChannels($bubble);
        }
    }

    protected function getHandleFromRecord($question)
    {
        return 'quizz_question_'.$question['external_id'];
    }

    protected function getFieldsFromRecord($question)
    {
        return $question;
    }

    public function getSourceJobKey()
    {
        $key = parent::getSourceJobKey();
        return $key.'_'.$this->question['external_id'];
    }
}
