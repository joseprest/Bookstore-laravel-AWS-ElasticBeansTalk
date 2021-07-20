<?php namespace Manivelle\View\Composers\Organisation\Screens\Tabs;

use View;
use Manivelle\User;
use Panneau;
use Route;
use DB;

use Illuminate\Http\Request;
use Manivelle\Models\ScreenPivot;

class ControlsComposer
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
        
        $view->screen = $screen;
        
        $view->pings = $screen->pings()
            ->orderBy('created_at', 'DESC')
            ->take(30)
            ->get();
            
        $view->commands = $screen->commands()
            ->orderBy(DB::raw('IFNULL(executed_at, NOW())'), 'DESC')
            ->take(30)
            ->get();
    }
}
