<?php

namespace Manivelle\Banq\Sources\Jobs;

use Panneau;
use Panneau\Exceptions\ResourceNotFoundException;

use Manivelle\Sources\Job;
use Manivelle\Sources\Traits\ValidateImage;

use Manivelle\Models\Bubble;
use Manivelle\Models\Channel;
use Illuminate\Log\Writer;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException;
use Exception;

use Imagick;

class SyncBanqQuizz extends Job
{
    use ValidateImage;

    protected $shouldNeverSkip = true;

    public function getSourceJobKey()
    {
        return 'banq_quizz';
    }

    public function sync()
    {
        $page = 1;
        do {
            $response = $this->request($page);

            //Get next page
            $nextUrl = array_get($response, 'next.$ref', '');
            if (!empty($nextUrl) && preg_match('/page\=([0-9]+)/', $nextUrl, $matches)) {
                $nextPage = (int)$matches[1];
            } else {
                $nextPage = null;
            }

            $isLastPage = !$nextPage ? true:false;
            $this->dispatch(new SyncBanqQuizzPage($page, $isLastPage));

            $page = $nextPage;
        } while ($page !== null);
    }

    protected function request($page = null)
    {
        $query = [];
        if ($page) {
            $query['page'] = $page;
        }

        try {
            $client = new HttpClient();
            $response = $client->request('GET', 'https://applications.banq.qc.ca/apex/faq/questions', [
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

    protected function getFieldsFromItem($item)
    {
        $fields = [];

        $fields['banq_id'] = trim(array_get($item, 'id_faq', ''));
        $fields['question'] = $this->cleanHtml(array_get($item, 'question', ''));
        $fields['image'] = 'http://www.banq.qc.ca'.trim(array_get($item, 'url_alt', ''));
        $fields['category'] = $this->cleanHtml(array_get($item, 'nom_categorie', ''));
        $fields['subcategory'] = $this->cleanHtml(array_get($item, 'nom_rubrique', ''));
        $fields['answers'] = [];
        $goodAnswer = strtolower(trim(array_get($item, 'choix_reponse_vrai', '')));
        $answerA = $this->cleanHtml(array_get($item, 'choix_reponse_a', ''));
        if (!empty($answerA)) {
            $fields['answers'][] = [
                'text' => $answerA,
                'good' => $goodAnswer === 'a' ? true:false,
                'explanation' => $goodAnswer === 'a' ? trim(array_get($item, 'reponse_vitrine', '')):''
            ];
        }
        $answerB = $this->cleanHtml(array_get($item, 'choix_reponse_b', ''));
        if (!empty($answerB)) {
            $fields['answers'][] = [
                'text' => $answerB,
                'good' => $goodAnswer === 'b' ? true:false,
                'explanation' => $goodAnswer === 'b' ? trim(array_get($item, 'reponse_vitrine', '')):''
            ];
        }
        $answerC = $this->cleanHtml(array_get($item, 'choix_reponse_c', ''));
        if (!empty($answerC)) {
            $fields['answers'][] = [
                'text' => $answerC,
                'good' => $goodAnswer === 'c' ? true:false,
                'explanation' => $goodAnswer === 'c' ? trim(array_get($item, 'reponse_vitrine', '')):''
            ];
        }

        return $fields;
    }

    protected function cleanHtml($text)
    {
        return trim(html_entity_decode(strip_tags($text), ENT_QUOTES, 'utf-8'));
    }

    protected function getHandleFromItem($item)
    {
        $id = array_get($item, 'id_faq', '-');
        $handle = 'banq_question_'.$id;

        return $handle;
    }
}
