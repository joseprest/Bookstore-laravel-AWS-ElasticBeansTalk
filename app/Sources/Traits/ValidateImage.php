<?php

namespace Manivelle\Sources\Traits;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException;
use Exception;

trait ValidateImage
{
    protected $minimumImageSize = 1;

    protected function imageIsValid($url)
    {
        try {
            $client = new HttpClient();
            $response = $client->request('GET', $url);
        } catch (RequestException $e) {
            return false;
        } catch (Exception $e) {
            return false;
        }

        if ($response->getStatusCode() !== 200) {
            return false;
        }

        $contenType = trim(implode('', $response->getHeader('Content-Type')));
        if (!preg_match('/^image\//', $contenType)) {
            return false;
        }

        $errorLevel = error_reporting();

        error_reporting(0);
        $image = @imagecreatefromstring($response->getBody());
        $width = $image ? @imagesx($image):0;
        $height = $image ? @imagesy($image):0;
        if ($image) {
            imagedestroy($image);
        }
        error_reporting($errorLevel);

        if (!$image || $width <= $this->minimumImageSize || $height <= $this->minimumImageSize) {
            return false;
        }

        return true;
    }
}
