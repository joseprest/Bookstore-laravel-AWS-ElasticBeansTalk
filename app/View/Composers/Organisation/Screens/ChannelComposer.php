<?php namespace Manivelle\View\Composers\Organisation\Screens;

use View;
use Manivelle\User;
use Panneau;
use Route;

use Illuminate\Http\Request;
use Manivelle\Models\ScreenPivot;

class ChannelComposer
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
        $screen = $view->item;
        $channel = $view->channel;
        
        //Get bubbles
        $filters = $channel->getSettingsFilters();
        //$filters = isset($channel->settings->filters) ? $channel->settings->filters:null;
        $list = Panneau::itemsList('bubbles.channel.filters')
                            ->with([
                                'screen' => $screen->screen,
                                'channel' => $channel->channel,
                                'filters' => $filters
                            ]);
        $view->list = $list;
    }
}
