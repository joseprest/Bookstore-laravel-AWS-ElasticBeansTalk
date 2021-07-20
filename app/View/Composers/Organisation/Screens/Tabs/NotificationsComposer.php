<?php namespace Manivelle\View\Composers\Organisation\Screens\Tabs;

use View;
use Manivelle\User;
use Panneau;
use Route;

use Illuminate\Http\Request;
use Manivelle\Models\ScreenPivot;

class NotificationsComposer
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
         * Playlists
         */
        $playlist = $screen->playlists()
                                    ->where('screens_playlists_pivot.organisation_id', $organisation->id)
                                    ->where('playlists.type', 'organisation.screen.notifications')
                                    ->first();
        
        $view->playlist = Panneau::itemsList('bubbles.playlist')
                                        ->withPlaylist($playlist)
                                        ->withItems($playlist->bubbles);
    }
}
