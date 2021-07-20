<?php namespace Manivelle\GraphQL\Type\ChannelFilter;

use GraphQL;
use GraphQL\Type\Definition\Type;

class ChannelFilterMapType extends ChannelFilterType
{
    
    public function attributes()
    {
        return [
            'name' => 'ChannelFilterMap',
            'description' => 'Channel filter map'
        ];
    }
    
    public function fields()
    {
        $fields = parent::fields();
        
        $fields['markerType'] = [
            'type' => Type::string(),
            'description' => 'The marker type'
        ];
        
        $fields['clusterIconType'] = [
            'type' => Type::string(),
            'description' => 'The marker cluster icon type'
        ];
        
        return $fields;
    }
}
