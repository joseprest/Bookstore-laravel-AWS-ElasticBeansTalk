<?php namespace Manivelle\GraphQL\Mutation;

use GraphQL;
use Request;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Mutation;

use Manivelle\Models\Playlist;
use Manivelle\Models\Bubble;

class PlaylistAddBubbles extends PlaylistAddBubble
{
    protected $attributes = [
        'description' => 'Add bubbles to a playlist'
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
            'bubble_ids' => [
                'name' => 'bubble_ids',
                'type' => Type::listOf(Type::string())
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
        $argsHasCondition = false;

        // If a condition is set in the args, all the new
        // bubbles will have it. If no condition is set, see
        // below, in the bubbles
        $conditionId = null;
        if (isset($args['condition_id'])) {
            $conditionId = $args['condition_id'];
            $argsHasCondition = true;
        } elseif (isset($args['conditions'])) {
            $conditions = json_decode($args['conditions'], true);
            $condition = $this->createCondition($conditions);
            $conditionId = $condition->id;
            $argsHasCondition = true;
        }

        $bubbles = [];
        foreach ($args['bubble_ids'] as $id) {
            $bubble = Bubble::find($id);

            if (!$bubble) {
                throw new \GraphQL\Error('Bubble not found');
            }

            $itemData = [];

            // If no condition was found in the args, we create
            // a new condition for each bubble (every
            // bubble will have its own condition)
            $bubbleConditionId = $conditionId;
            if (!$argsHasCondition) {
                $condition = $this->createCondition([]);
                $bubbleConditionId = $condition->id;
            }

            if (isset($bubbleConditionId)) {
                $itemData['condition_id'] = $bubbleConditionId;
            }

            $bubble = $playlist->addItem($bubble, $itemData);

            $bubbles[] = $bubble;
        }

        return $bubbles;
    }
}
