<?php namespace Manivelle\View\Composers\Organisation\Screens\Tabs;

use View;
use Manivelle\User;
use Panneau;
use Route;

use Illuminate\Http\Request;
use Manivelle\Models\ScreenPivot;

class SettingsComposer
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
        $screen = $view->item instanceof ScreenPivot ? $view->item->screen:$view->item;
        /**
         * Settings
         */
        $view->form = Panneau::form('screen.settings')
                                        ->withModel($screen)
                                        ->withRequest($this->request);
                                        
        /**
         * Unlink
         */
        $view->unlinkForm = Panneau::form('screen.unlink')
                                        ->withModel($screen)
                                        ->withRequest($this->request);
    }
}
