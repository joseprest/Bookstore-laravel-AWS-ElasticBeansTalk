<?php namespace Manivelle\View\Composers\Organisation\Screens\Tabs;

use View;
use Manivelle\User;
use Panneau;
use Route;
use Manivelle;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Manivelle\Models\ScreenPivot;

class SlideshowComposer
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
        $playlist = $screen->playlistsSlideshow()->first();
        $screenPlaylists = $organisation->playlistScreens()
            ->where('playlists.type', 'organisation.screen.slideshow')
            ->with('screens')
            ->get();
        $organisationPlaylists = $organisation->playlists()
            ->where('playlists.type', 'organisation.screen.slideshow')
            ->with('screens')
            ->get();
        $playlists = $screenPlaylists
            ->merge($organisationPlaylists)
            ->unique();
        
        //Playlist
        $view->playlist = Panneau::itemsList('playlist')
                                        ->withAttributes([
                                            'screen' => $screen,
                                            'playlist' => $playlist ? $playlist->id:null,
                                            'playlists' => $playlists
                                        ]);
        
        //Timeline
        $view->timeline = Panneau::itemsList('bubbles.timeline')
                                    ->withAttributes([
                                        'screen_id' => $screen->id
                                    ]);
    }
}
