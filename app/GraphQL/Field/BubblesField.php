<?php namespace Manivelle\GraphQL\Field;

use GraphQL;
use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\Field;

class BubblesField extends Field
{
    public function type()
    {
        return Type::listOf(GraphQL::type('Bubble'));
    }
    
    public function resolve($root)
    {
        return $root->bubbles;
    }
}
