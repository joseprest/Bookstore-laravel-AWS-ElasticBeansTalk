<?php namespace Manivelle\GraphQL\Mutation;

use GraphQL;
use Request;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Mutation;

use Manivelle\Models\Screen;
use Manivelle\Models\Playlist;
use Manivelle\Models\Organisation;

class PlaylistDelete extends Mutation
{
    protected $attributes = [
        'description' => 'Delete a playlist'
    ];
    
    public function type()
    {
        return GraphQL::type('Playlist');
    }
    
    public function args()
    {
        return [
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
        $playlist = Playlist::find($args['playlist_id']);
        
        if (!$playlist) {
            throw new \GraphQL\Error('Playlist not found');
        }
        
        if (isset($args['organisation_id'])) {
            $organisation = Organisation::find($args['organisation_id']);
        } else {
            $organisation = Request::route('organisation');
        }
        
        $playlist->delete();
        
        return $playlist;
    }
}
