<?php namespace Manivelle\View\Composers\Organisation\Invitation;

use View;
use Manivelle\User;
use Panneau;
use Route;
use Illuminate\Http\Request;

class LinkComposer
{
    protected $request;
    
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    
    public function compose($view)
    {
    }
}
