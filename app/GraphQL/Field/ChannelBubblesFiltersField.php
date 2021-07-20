<?php namespace Manivelle\GraphQL\Field;

use GraphQL;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ResolveInfo;
use Folklore\GraphQL\Support\Field;

class ChannelBubblesFiltersField extends Field
{
    public function attributes()
    {
        return [
            'description' => 'The bubbles filters of a channel'
        ];
    }
    
    public function type()
    {
        return Type::listOf(GraphQL::type('ChannelBubbleFilterInterface'));
    }
    
    public function resolve($root, $args, ResolveInfo $info)
    {
        $fields = $info->getFieldSelection();
        
        if (!isset($fields['values']) || !$fields['values']) {
            return $root->getBubblesFilters();
        } else {
            return $root->bubbles_filters;
        }
    }
}
