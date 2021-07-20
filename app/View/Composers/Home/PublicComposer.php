<?php namespace Manivelle\View\Composers\Home;

use Manivelle;

use Illuminate\Http\Request;

class PublicComposer
{
    protected $request;
    
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    
    public function compose($view)
    {
        $view->form = Manivelle::form('auth.login')
                                    ->withRequest($this->request);
    }
}
