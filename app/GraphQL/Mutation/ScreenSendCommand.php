<?php namespace Manivelle\GraphQL\Mutation;

use GraphQL;
use Request;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Mutation;

use Manivelle\Models\Screen;
use Manivelle\Models\Organisation;
use Manivelle\Models\Condition;

class ScreenSendCommand extends Mutation
{
    protected $attributes = [
        'description' => 'Send command to screen'
    ];
    
    public function type()
    {
        return GraphQL::type('ScreenCommand');
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
            'command' => [
                'name' => 'command',
                'type' => Type::string(),
                'rules' => ['required']
            ],
            'arguments' => [
                'name' => 'arguments',
                'type' => Type::string()
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
        
        $commandArgs = array_get($args, 'arguments', []);
        $commandArgs = is_string($commandArgs) ? json_decode($commandArgs):$commandArgs;
        return $screen->addCommand($args['command'], $commandArgs);
    }
}
