<?php namespace Manivelle\View\Composers\Auth;

use Manivelle;

use Illuminate\Http\Request;

class PasswordsEmailComposer
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function compose($view)
    {
        $view->form = Manivelle::form('auth.reset_email')
                                    ->withRequest($this->request);
    }
}
