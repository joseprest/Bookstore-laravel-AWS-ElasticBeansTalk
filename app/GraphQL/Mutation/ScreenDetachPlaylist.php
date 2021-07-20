<?php namespace Manivelle\GraphQL\Mutation;

use GraphQL;
use Request;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Mutation;

use Manivelle\Models\Screen;
use Manivelle\Models\Playlist;
use Manivelle\Models\Organisation;

class ScreenDetachPlaylist extends Mutation
{
    protected $attributes = [
        'description' => 'Dissociate a playlist to a screen'
    ];
    
    public function type()
    {
        return GraphQL::type('Playlist');
    }
    
    public function args()
    {
        return [
            'screen_id' => [
                'name' => 'screen_id',
                'type' => Type::string(),
                'rules' => ['required', 'exists:screens,id']
            ],
            'playlist_id' => [
                'name' => 'playlist_id',
                'type' => Type::string(),
                'rules' => ['required', 'exists:playlists,id']
            ],
            'organisation_id' => [
                'name' => 'organisation_id',
                'type' => Type::string(),
                'rules' => ['exists:organisations,id']
            ]
        ];
    }
    
    public function resolve($root, $args)
    {
        $screen = Screen::findOrFail($args['screen_id']);
        $playlist = Playlist::findOrFail($args['playlist_id']);
        
        if (isset($args['organisation_id'])) {
            $organisation = Organisation::find($args['organisation_id']);
        } else {
            $organisation = Request::route('organisation');
        }
        
        $playlist = $screen->detachPlaylist($playlist, $organisation);
        
        return $playlist;
    }
}
