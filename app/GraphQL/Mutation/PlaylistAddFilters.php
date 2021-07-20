<?php namespace Manivelle\GraphQL\Mutation;

use GraphQL;
use Request;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Mutation;

use Manivelle\Models\Playlist;
use Manivelle\Models\Bubble;

class PlaylistAddFilters extends PlaylistAddBubble
{
    protected $attributes = [
        'description' => 'Add filters to a playlist'
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
            'filters' => [
                'name' => 'filters',
                'type' => Type::string()
            ],
            'condition_id' => [
                'name' => 'condition_id',
                'type' => Type::string(),
                'rules' => ['exists:conditions,id']
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
        
        $filters = json_decode($args['filters'], true);
        
        $filterBubble = new Bubble();
        $filterBubble->type = 'filter';
        $filterBubble->save();
        $filterBubble->saveFields([
            'filters' => $filters
        ]);
        
        $conditionId = null;
        if (isset($args['condition_id'])) {
            $conditionId = $args['condition_id'];
        } elseif (isset($args['conditions'])) {
            $conditions = json_decode($args['conditions'], true);
            $condition = $this->createCondition($conditions);
            $conditionId = $condition->id;
        } else {
            $condition = $this->createCondition([]);
            $conditionId = $condition->id;
        }
        
        $itemData = [];
        if ($conditionId) {
            $itemData['condition_id'] = $conditionId;
        }
        
        return $playlist->addItem($filterBubble, $itemData);
    }
}
