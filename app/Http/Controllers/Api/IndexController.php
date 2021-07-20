<?php namespace Manivelle\Http\Controllers\Api;

use Illuminate\Http\Request;
use Manivelle\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function token()
    {
        return [
            'token' => csrf_token()
        ];
    }
}
