<?php

namespace Manivelle\Http\Controllers;

use Auth;
use Panneau\Http\Controllers\Controller as BaseController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests;
    
    protected $user;
    
    public function __construct()
    {
        $this->user = Auth::check() ? Auth::user():null;
    }
}
