<?php namespace Manivelle\View\Composers\Organisation\Screens\Tabs;

use View;
use Manivelle\User;
use Panneau;
use Route;
use Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Manivelle\Models\ScreenPivot;

class ChannelsComposer
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
        $user = Auth::user();
        
        $organisation = $this->organisation;
        $screen = $view->item instanceof ScreenPivot ? $view->item->screen:$view->item;
        $screen->loadIfNotLoaded([
            'channels.channel',
            'channels.channel.metadatas',
            'channels.channel.pictures',
            'channels.channel.texts'
        ]);
        
        $filteredChannels = $screen->channels->filter(function ($channel) use ($organisation) {
            return (int)$channel->organisation_id === (int)$organisation->id;
        });
        
        $channels = new Collection();
        foreach ($filteredChannels as $channel) {
            $channel->withoutFilters();
            $channels->push($channel);
        }
        
        //Get channels
        $canManageChannels = $user->can('screenManageChannels', $organisation);
        $list = Panneau::itemsList('channels')
                            ->with([
                                'name' => 'screen.channels',
                                'screen' => $screen,
                                'withAddButton' => $canManageChannels,
                                'withRemoveButton' => $canManageChannels
                            ])
                            ->setItems($channels);
        
        $view->list = $list;
    }
}
