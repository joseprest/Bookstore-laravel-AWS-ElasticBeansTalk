<?php namespace Manivelle\View\Composers\Organisation\Team;

use View;
use Manivelle;
use Manivelle\User;
use Panneau;
use Route;

use Illuminate\Http\Request;

class ShowComposer
{
    protected $request;
    protected $organisation;
    
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->organisation = $request->organisation;
    }
    
    public function compose($view)
    {
        $organisation = $this->organisation;
        $user = $view->item;
        
        
        $view->form = Manivelle::form('team')
                                    ->withRequest($this->request)
                                    ->withModel($user);
    }
}
