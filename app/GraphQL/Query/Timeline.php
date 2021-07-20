<?php namespace Manivelle\GraphQL\Query;

use GraphQL;
use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\Query;
use GraphQL\Type\Definition\ResolveInfo;
use Manivelle;
use Manivelle\Models\Screen;
use Panneau;

class Timeline extends Query
{
    
    protected $attributes = [
        'description' => 'Timeline query'
    ];
    
    public function type()
    {
        return GraphQL::type('Timeline');
    }
    
    public function args()
    {
        return [
            'screen_id' => [
                'name' => 'screen_id',
                'type' => Type::string()
            ],
            'cache' => [
                'name' => 'cache',
                'type' => Type::boolean()
            ]
        ];
    }
    
    public function resolve($root, $args, ResolveInfo $info)
    {
        $cache = isset($args['cache']) ? $args['cache']:true;
        $type = array_get($args, 'type', 'organisation.screen.slideshow');
        
        $screen = Screen::find($args['screen_id']);
        $playlist = $screen->playlists()
            ->where('playlists.type', $type)
            ->first();
        if (!$playlist) {
            return [];
        }
        
        $timeline = $playlist->getTimeline();
        
        $fields = $info->getFieldSelection();
        $hasBubbles = array_get($fields, 'bubbles', false);
        if ($hasBubbles) {
            $timeline->bubbles = Panneau::resource('bubbles')->get([
                'id' => $timeline->bubbleIds
            ]);
        }
        
        return $timeline;
    }
}
