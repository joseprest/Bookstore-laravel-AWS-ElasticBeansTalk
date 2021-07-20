<?php

namespace Manivelle\Sources\QuizzCSV\Jobs;

use Manivelle\Support\SyncJob;
use Manivelle\Jobs\CreateImagesJob;
use GuzzleHttp\Client as HttpClient;

use Panneau;
use DB;
use Exception;
use Panneau\Exceptions\ResourceNotFoundException;

use Carbon\Carbon;
use Illuminate\Log\Writer;

use Manivelle\Sources\Job;

use Illuminate\Support\Str;

class SyncQuizzCSV extends Job
{
    protected $shouldNeverSkip = true;

    public $path;
    public $imagesFolder;

    public function __construct($path, $imagesFolder = null)
    {
        $this->path = $path;
        $this->imagesFolder = $imagesFolder;
    }

    public function sync()
    {
        $file = fopen($this->path, "r");
        if ($file === false) {
            throw new Exception('Quizz CSV "'.$path.'" not found.');
        }
        $questions = [];
        $questionIndex = 0;
        while (($data = fgetcsv($file)) !== false) {
            if ($questionIndex === 0) {
                $questionIndex++;
                continue;
            }
            $answersLetters = ['A', 'B', 'C'];
            $good = $data[5];
            $explanation = $data[6];
            $goodIndex = array_search($good, $answersLetters);
            $answers = [];
            for ($i = 0; $i < 3; $i++) {
                $index = 2 + $i;
                if (!empty($data[$index])) {
                    $answers[] = [
                        'text' => $data[$index],
                        'explanation' => $goodIndex === $i ? $explanation : null,
                        'good' => $goodIndex === $i,
                    ];
                }
            }
            $question = [
                'external_id' => md5($this->path).'_'.$questionIndex,
                'category' => $data[0],
                'question' => $data[1],
                'image' => !empty($this->imagesFolder) ? (rtrim($this->imagesFolder, '/').'/'.$data[7]) : null,
                'answers' => $answers,
            ];
            $questions[] = $question;
            $questionIndex++;
        }
        fclose($file);

        $i = 0;
        $questionsCount = sizeof($questions);
        foreach ($questions as $question) {
            $isLast = $i === ($questionsCount - 1) ? true : false;
            $job = new SyncQuizzQuestion($question, $isLast);
            $this->dispatch($job);
            $i++;
        }
    }

    public function getSourceJobKey()
    {
        return 'quizzcsv_'.md5($this->path);
    }
}
