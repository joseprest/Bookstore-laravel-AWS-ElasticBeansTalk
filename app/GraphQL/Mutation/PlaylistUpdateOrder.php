<?php namespace Manivelle\GraphQL\Mutation;

use GraphQL;
use Request;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Mutation;

use Manivelle\Models\Playlist;
use Manivelle\Models\Bubble;

class PlaylistUpdateOrder extends Mutation
{
    protected $attributes = [
        'description' => 'Update order of a playlist'
    ];
    
    public function type()
    {
        return Type::listOf(GraphQL::type('PlaylistItem'));
    }
    
    public function args()
    {
        return [
            'playlist_id' => [
                'name' => 'playlist_id',
                'type' => Type::string(),
                'rules' => ['required', 'exists:playlists,id']
            ],
            'ids' => [
                'name' => 'ids',
                'type' => Type::listOf(Type::int()),
                'rules' => ['required']
            ]
        ];
    }
    
    public function resolve($root, $args)
    {
        $playlist = Playlist::find($args['playlist_id']);
        
        $ids = $args['ids'];
        return $playlist->updateOrder($ids);
    }
}
