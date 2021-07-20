<?php namespace Manivelle\GraphQL\Query;

use GraphQL;
use Panneau;

use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\Query;

class ResourceQuery extends Query
{
    protected $resource;
    
    public function resolve($root, $args)
    {
        $resource = Panneau::resource($this->resource);
        return $resource->find(isset($args['id']) ? $args['id']:$args);
    }
}
