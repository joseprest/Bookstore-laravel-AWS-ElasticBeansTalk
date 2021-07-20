<?php namespace Manivelle\View\Composers\Account;

use View;
use Manivelle\User;
use Manivelle;
use Route;

use Illuminate\Http\Request;

class IndexComposer
{
    protected $request;
    
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    
    public function compose($view)
    {
        $view->form = Manivelle::form('account')
                                    ->withModel($view->item)
                                    ->withRequest($this->request);
                                    
        $view->deleteForm = Manivelle::form('account.delete')
                                    ->withModel($view->item)
                                    ->withRequest($this->request);
    }
}
