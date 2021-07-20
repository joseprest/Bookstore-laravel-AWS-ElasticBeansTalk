<?php namespace Manivelle\GraphQL\Mutation;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Mutation;

use Manivelle\Models\Channel;

class ChannelRemoveBubble extends Mutation
{
    protected $attributes = [
        'description' => 'Remove a bubble from a channel'
    ];

    public function type()
    {
        return GraphQL::type('Bubble');
    }

    public function args()
    {
        return [
            'channel_id' => [
                'name' => 'channel_id',
                'type' => Type::string(),
                'rules' => ['exists:channels,id']
            ],
            'bubble_id' => [
                'name' => 'bubble_id',
                'type' => Type::string(),
                'rules' => ['required', 'exists:bubbles,id']
            ]
        ];
    }

    public function resolve($root, $args)
    {
        $channel = Channel::find($args['channel_id']);

        if (!$channel) {
            throw new GraphQL\Error('Channel not found');
        }

        $bubble = $channel->removeBubble($args['bubble_id']);

        if (!$bubble) {
            throw new GraphQL\Error('Bubble not in channel');
        }

        return $bubble;
    }
}
