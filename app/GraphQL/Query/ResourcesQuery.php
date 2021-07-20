<?php namespace Manivelle\GraphQL\Query;

use GraphQL;
use Panneau;

use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\Query;

class ResourcesQuery extends Query
{
    protected $resource;
    
    protected $defaultPage = null;
    protected $defaultCount = null;
    
    public function args()
    {
        return [
            'page' => ['name' => 'page', 'type' => Type::int()],
            'count' => ['name' => 'count', 'type' => Type::int()]
        ];
    }
    
    public function resolve($root, $args)
    {
        $resource = Panneau::resource($this->resource);
        $page = array_get($args, 'page', $this->defaultPage);
        $count = array_get($args, 'count', $this->defaultCount);
        $items = $resource->get($args, $page, $count);
        return $items;
    }
}
