<?php namespace Manivelle\GraphQL\Field;

use GraphQL;
use GraphQL\Type\Definition\Type;

class ChannelSettingsField extends TextLocaleField
{
    public function type()
    {
        return GraphQL::type('ChannelSettings');
    }
    
    public function resolve($root, $args = [])
    {
        $fields = $root->fields;
        $name = $this->name;
        
        return $fields->{$name};
    }
}
