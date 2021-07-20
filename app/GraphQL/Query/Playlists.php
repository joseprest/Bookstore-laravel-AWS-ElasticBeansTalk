<?php namespace Manivelle\GraphQL\Query;

use GraphQL;
use GraphQL\Type\Definition\Type;

class Playlists extends ResourcesQuery
{
    protected $resource = 'playlists';
    
    protected $attributes = [
        'description' => 'Playlists query'
    ];
    
    public function type()
    {
        return Type::listOf(GraphQL::type('Playlist'));
    }
    
    public function args()
    {
        $args = parent::args();
        
        $args['screen_id'] = ['name' => 'screen_id', 'type' => Type::string()];
        $args['organisation_id'] = ['name' => 'organisation_id', 'type' => Type::string()];
        
        return $args;
    }
}
