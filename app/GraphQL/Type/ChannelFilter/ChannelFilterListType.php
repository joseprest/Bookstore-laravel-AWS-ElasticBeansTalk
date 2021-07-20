<?php namespace Manivelle\GraphQL\Type\ChannelFilter;

use GraphQL;
use GraphQL\Type\Definition\Type;

class ChannelFilterListType extends ChannelFilterType
{
    
    public function attributes()
    {
        return [
            'name' => 'ChannelFilterList',
            'description' => 'Channel filter list'
        ];
    }
    
    public function fields()
    {
        $fields = parent::fields();
        
        $fields['layout'] = [
            'type' => Type::string(),
            'description' => 'The list layout'
        ];
        
        return $fields;
    }
}
