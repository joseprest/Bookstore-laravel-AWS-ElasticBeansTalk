<?php namespace Manivelle\GraphQL\Type\ChannelBubbleFilter;

use GraphQL;
use Closure;
use GraphQL\Type\Definition\Type;

class ChannelBubbleFilterSelectType extends ChannelBubbleFilterType
{
    
    public function attributes()
    {
        return [
            'name' => 'ChannelBubbleFilterSelect',
            'description' => 'Channel bubble filter'
        ];
    }
    
    public function fields()
    {
        $fields = parent::fields();
        
        $fields['values'] = [
            'type' => Type::listOf(GraphQL::type('SelectValue')),
            'resolve' => function ($root) {
                
                $values = [];
                if ($root['values'] instanceof Closure) {
                    $values = $root['values']();
                } elseif ($root['values']) {
                    $values = $root['values'];
                }
                
                return $values;
            }
        ];
        
        return $fields;
    }
}
