<?php namespace Manivelle\GraphQL\Type;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Manivelle\Support\ChannelType as ManivelleChannelType;

use Folklore\GraphQL\Support\InterfaceType as GraphQLInterfaceType;

class ChannelFieldsInterface extends GraphQLInterfaceType
{
    protected $attributes = [
        'name' => 'ChannelFieldsInterface',
        'description' => 'Fields'
    ];
    
    public function fields()
    {
        $channelType = new ManivelleChannelType();
        $channelFields = $channelType->getFields();
        
        $fields = [];
        foreach ($channelFields as $channelField) {
            $name = $channelField->name;
            $type = preg_replace('/^channel[\_\-]/', '', $channelField->type);
            $fieldClass = '\Manivelle\GraphQL\Field\\Channel'.studly_case($type).'Field';
            if (class_exists($fieldClass)) {
                $fields[$name] = $fieldClass;
            } else {
                $fields[$name] = [
                    'type' => Type::string(),
                    'description' => 'The '.$name.' of the channel'
                ];
            }
        }
        
        return $fields;
    }
    
    public function resolveType($item)
    {
        try {
            $name = 'Channel'.studly_case($item->type).'Fields';
            return GraphQL::type($name);
        } catch (\Exception $e) {
            return GraphQL::type('ChannelFields');
        }
    }
}
