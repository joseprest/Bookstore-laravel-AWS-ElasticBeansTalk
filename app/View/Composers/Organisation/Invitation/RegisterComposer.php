<?php namespace Manivelle\View\Composers\Organisation\Invitation;

use View;
use Manivelle\User;
use Panneau;
use Route;
use Illuminate\Http\Request;

class RegisterComposer
{
    protected $request;
    
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    
    public function compose($view)
    {
        $form = Panneau::form('invitation.register')
                        ->withModel($view->invitation)
                        ->withRequest($this->request);
        $view->form = $form;
    }
}
