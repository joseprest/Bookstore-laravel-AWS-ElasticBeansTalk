<?php namespace Manivelle\GraphQL\Field;

use GraphQL;
use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\Field;

class ScreensField extends Field
{
    public function type()
    {
        return Type::listOf(GraphQL::type('Screen'));
    }
    
    public function resolve($root)
    {
        return $root->screens;
    }
}
