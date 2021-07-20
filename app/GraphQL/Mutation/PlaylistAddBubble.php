<?php namespace Manivelle\GraphQL\Mutation;

use GraphQL;
use Request;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Mutation;

use Manivelle\Models\Playlist;
use Manivelle\Models\Bubble;
use Manivelle\Models\Condition;

class PlaylistAddBubble extends Mutation
{
    protected $attributes = [
        'description' => 'Add a bubble to a playlist'
    ];
    
    public function type()
    {
        return GraphQL::type('PlaylistItem');
    }
    
    public function args()
    {
        return [
            'playlist_id' => [
                'name' => 'playlist_id',
                'type' => Type::string(),
                'rules' => ['required', 'exists:playlists,id']
            ],
            'bubble_id' => [
                'name' => 'bubble_id',
                'type' => Type::string(),
                'rules' => ['required', 'exists:bubbles,id']
            ],
            'order' => [
                'name' => 'order',
                'type' => Type::int()
            ],
            'condition_id' => [
                'name' => 'condition_id',
                'type' => Type::string(),
                'rules' => ['required', 'exists:conditions,id']
            ],
            'conditions' => [
                'name' => 'conditions',
                'type' => Type::string()
            ]
        ];
    }
    
    public function resolve($root, $args)
    {
        $playlist = Playlist::find($args['playlist_id']);
        
        if (!$playlist) {
            throw new \GraphQL\Error('Playlist not found');
        }
        
        $bubble = Bubble::find($args['bubble_id']);
        
        if (!$bubble) {
            throw new \GraphQL\Error('Bubble not found');
        }
        
        $conditionId = null;
        if (isset($args['condition_id'])) {
            $conditionId = $args['condition_id'];
        } elseif (isset($args['conditions'])) {
            $conditions = json_decode($args['conditions'], true);
            $condition = $this->createCondition($conditions);
            $conditionId = $condition->id;
        }
        
        $itemData = [];
        if (isset($args['order'])) {
            $itemData['order'] = $args['order'];
        }
        if ($conditionId) {
            $itemData['condition_id'] = $conditionId;
        }
        
        return $playlist->addItem($bubble, $itemData);
    }
    
    protected function createCondition($conditions)
    {
        $organisation = Request::route('organisation');
        $condition = new Condition();
        if ($organisation) {
            $condition->organisation_id = $organisation->id;
        }
        $condition->save();
        $condition->saveFields($conditions);
        return $condition;
    }
}
