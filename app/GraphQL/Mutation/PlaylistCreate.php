<?php namespace Manivelle\GraphQL\Mutation;

use GraphQL;
use Request;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Mutation;

use Manivelle\Models\Screen;
use Manivelle\Models\Playlist;
use Manivelle\Models\Organisation;

class PlaylistCreate extends Mutation
{
    protected $attributes = [
        'description' => 'Create a playlist'
    ];
    
    public function type()
    {
        return GraphQL::type('Playlist');
    }
    
    public function args()
    {
        return [
            'screen_id' => [
                'name' => 'screen_id',
                'type' => Type::string(),
                'rules' => ['required', 'exists:screens,id']
            ],
            'organisation_id' => [
                'name' => 'organisation_id',
                'type' => Type::string(),
                'rules' => ['exists:organisations,id']
            ],
            'name' => [
                'name' => 'name',
                'type' => Type::string(),
                'rules' => ['required']
            ],
            'type' => [
                'name' => 'type',
                'type' => Type::string()
            ]
        ];
    }
    
    public function resolve($root, $args)
    {
        $screen = Screen::find($args['screen_id']);
        
        if (!$screen) {
            throw new \GraphQL\Error('Screen not found');
        }
        
        if (isset($args['organisation_id'])) {
            $organisation = Organisation::find($args['organisation_id']);
        } else {
            $organisation = Request::route('organisation');
        }
        
        $playlist = new Playlist();
        $playlist->organisation_id = $organisation->id;
        $playlist->type = array_get($args, 'type', 'organisation.screen.slideshow');
        $playlist->name = array_get($args, 'name');
        $playlist->save();
        
        return $playlist;
    }
}
