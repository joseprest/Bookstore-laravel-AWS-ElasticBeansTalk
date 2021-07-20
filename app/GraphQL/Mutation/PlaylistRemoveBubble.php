<?php namespace Manivelle\GraphQL\Mutation;

use GraphQL;
use Request;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Mutation;

use Manivelle\Models\Playlist;
use Manivelle\Models\Bubble;

class PlaylistRemoveBubble extends Mutation
{
    protected $attributes = [
        'description' => 'Add a bubble to a playlist'
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
            'item_id' => [
                'name' => 'item_id',
                'type' => Type::string(),
                'rules' => ['required', 'exists:playlists_bubbles_pivot,id']
            ]
        ];
    }
    
    public function resolve($root, $args)
    {
        $playlist = Playlist::find($args['playlist_id']);
        
        if (!$playlist) {
            throw new \GraphQL\Error('Playlist not found');
        }
        
        $removedBubble = null;
        $itemId = $args['item_id'];
        
        $playlist->removeItem($itemId);
        
        return $playlist->items()
                        ->with(
                            'bubble.texts',
                            'bubble.metadatas',
                            'bubble.pictures',
                            'condition',
                            'condition.texts',
                            'condition.metadatas'
                        )
                        ->orderBy('order', 'asc')
                        ->get();
    }
}
