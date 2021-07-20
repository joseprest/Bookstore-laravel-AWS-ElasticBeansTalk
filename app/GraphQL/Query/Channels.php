<?php namespace Manivelle\GraphQL\Query;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Panneau;
use Manivelle\Models\Screen;
use Request;
use Auth;

class Channels extends ResourcesQuery
{
    protected $resource = 'channels';
    
    protected $attributes = [
        'description' => 'Channels query'
    ];
    
    public function type()
    {
        return Type::listOf(GraphQL::type('Channel'));
    }
    
    public function args()
    {
        $args = parent::args();
        
        $args['screen_id'] = [
            'name' => 'screen_id',
            'type' => Type::string()
        ];
        
        $args['id'] = [
            'name' => 'id',
            'type' => Type::string()
        ];
        
        return $args;
    }
    
    public function resolve($root, $args)
    {
        if (isset($args['screen_id'])) {
            $screen = Screen::find($args['screen_id']);
            $query = $screen->channels()
                    ->with([
                        'channel',
                        'channel.metadatas',
                        'channel.texts',
                        'channel.pictures'
                    ])
                    ->groupBy('screens_channels_pivot.channel_id');
            
            if (isset($args['id'])) {
                $query->where('screens_channels_pivot.channel_id', $args['id']);
            }
            
            return $query->get();
        }
        
        $user = Auth::user();
        $organisation = Request::route('organisation');
        if (!$user->is('admin') && $organisation) {
            $args['for_organisation'] = $organisation->id;
        }
        
        return parent::resolve($root, $args);
    }
}
