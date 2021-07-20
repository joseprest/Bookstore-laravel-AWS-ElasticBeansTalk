<?php namespace Manivelle\GraphQL\Mutation;

use GraphQL;
use Request;
use Auth;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Mutation;

use Manivelle\Models\Screen;
use Manivelle\Models\Channel;

class ScreenRemoveChannel extends Mutation
{
    protected $attributes = [
        'description' => 'Remove a channel from a screen'
    ];
    
    public function type()
    {
        return GraphQL::type('Channel');
    }
    
    public function args()
    {
        return [
            'screen_id' => [
                'name' => 'screen_id',
                'type' => Type::string(),
                'rules' => ['required', 'exists:screens,id']
            ],
            'channel_id' => [
                'name' => 'channel_id',
                'type' => Type::string(),
                'rules' => ['required', 'exists:channels,id']
            ]
        ];
    }
    
    public function resolve($root, $args)
    {
        $organisation = Request::route('organisation');
        if (!Auth::user()->can('screenManageChannels', $organisation)) {
            return abort(403);
        }
        
        $screen = Screen::find($args['screen_id']);
        
        if (!$screen) {
            throw new \GraphQL\Error('Screen not found');
        }
        
        $channel = Channel::find($args['channel_id']);
        
        if (!$channel) {
            throw new \GraphQL\Error('Channel not found');
        }
        
        $organisation = Request::route('organisation');
        
        return $screen->detachChannel($channel, $organisation);
    }
}
