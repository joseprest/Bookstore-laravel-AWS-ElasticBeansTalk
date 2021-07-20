<?php namespace Manivelle\GraphQL\Field;

use GraphQL;
use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\Field;

class ChannelFiltersField extends Field
{
    public function attributes()
    {
        return [
            'description' => 'The filters of a channel'
        ];
    }
    
    public function type()
    {
        return Type::listOf(GraphQL::type('ChannelFilterInterface'));
    }
    
    public function resolve($root)
    {
        return $root->filters;
    }
}
