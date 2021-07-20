<?php


namespace Manivelle\Cache\Traits;

use Request;
use Log;
use Manivelle\Exceptions\GraphQLException;

trait GraphQLRequest
{
    public function requestGraphQL($query, $params = [])
    {
        $response = app('graphql')->query($query, $params);

        if (Request::has('DEBUG_GRAPHQL')) {
            dd($response);
        }

        //Get data
        $data = array_get($response, 'data', []);
        if (!$data) {
            $data = null;
        }

        //Log errors
        $errors = array_get($response, 'errors', []);
        if ($errors && sizeof($errors)) {
            $messages = [];
            foreach ($errors as $error) {
                $messages[] = $error['message'];
            }

            Log::error(new GraphQLException(json_encode([
                'errors' => $messages,
                'params' => $params,
                'query' => $query
            ])));
        }

        return $data;
    }
}
