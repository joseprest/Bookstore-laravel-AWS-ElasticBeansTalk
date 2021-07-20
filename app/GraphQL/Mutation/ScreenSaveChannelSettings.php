<?php namespace Manivelle\GraphQL\Mutation;

use GraphQL;
use Request;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Mutation;

use Manivelle\Models\Screen;
use Manivelle\Models\Organisation;
use Manivelle\Models\Condition;

class ScreenSaveChannelSettings extends Mutation
{
    protected $attributes = [
        'description' => 'Save channel settings of a screen'
    ];
    
    public function type()
    {
        return GraphQL::type('Channel');
    }
    
    public function args()
    {
        return [
            'organisation_id' => [
                'name' => 'organisation_id',
                'type' => Type::string(),
                'rules' => ['exists:organisations,id']
            ],
            'screen_id' => [
                'name' => 'screen_id',
                'type' => Type::string(),
                'rules' => ['required', 'exists:screens,id']
            ],
            'channel_id' => [
                'name' => 'channel_id',
                'type' => Type::string(),
                'rules' => ['required', 'exists:channels,id']
            ],
            'settings' => [
                'name' => 'settings',
                'type' => Type::string(),
                'rules' => ['required']
            ]
        ];
    }
    
    public function resolve($root, $args)
    {
        if (isset($args['organisation_id'])) {
            $organisation = Organisation::find($args['organisation_id']);
        } else {
            $organisation = Request::route('organisation');
        }
        
        if (!$organisation) {
            throw new \GraphQL\Error('Organisation not found');
        }
        
        $screenId = $args['screen_id'];
        $screen = $organisation->screens->first(function ($key, $screen) use ($screenId) {
            return (int)$screen->screen_id === (int)$screenId;
        });
        
        if (!$screen) {
            throw new \GraphQL\Error('Screen not found');
        }
        
        $settings = is_string($args['settings']) ? json_decode($args['settings']):$args['settings'];
        return $screen->saveChannelSettings($args['channel_id'], $settings);
    }
}
