<?php namespace Manivelle\GraphQL\Query;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Query;

use Manivelle\Models\Playlist;

class PlaylistItems extends Query
{
    protected $attributes = [
        'description' => 'Playlists items'
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
                'type' => Type::string()
            ]
        ];
    }
    
    public function resolve($root, $args)
    {
        $playlist = Playlist::find($args['playlist_id']);
        
        $playlist->loadIfNotLoaded([
            'items',
            'items.condition',
            'items.bubble',
            'items.bubble.metadatas',
            'items.bubble.pictures',
            'items.bubble.texts'
        ]);
        
        return $playlist->items;
    }
}
