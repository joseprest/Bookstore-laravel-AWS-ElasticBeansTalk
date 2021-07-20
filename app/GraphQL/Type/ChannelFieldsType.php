<?php namespace Manivelle\GraphQL\Type;

use GraphQL;
use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\Type as GraphQLType;

use Manivelle\Support\ChannelType as ManivelleChannelType;

class ChannelFieldsType extends GraphQLType
{
    protected $channelType;
    
    public function attributes()
    {
        $name = $this->channelType ? ('Channel'.studly_case($this->channelType->type).'Fields'):'ChannelFields';
        return [
            'name' => $name,
            'description' => 'Channel fields'
        ];
    }
    
    public function setChannelType($channelType)
    {
        $this->channelType = $channelType;
    }
    
    public function fields()
    {
        $fields = [];
        
        $channelType = $this->channelType ? $this->channelType:new ManivelleChannelType();
        
        $channelTypeFields = $channelType->getFields();
        foreach ($channelTypeFields as $field) {
            $name = $field->name;
            $type = preg_replace('/^channel[\_\-]/', '', $field->type);
            $label = $field->label;
            
            $fieldClass = '\Manivelle\GraphQL\Field\Channel'.studly_case($type).'Field';
            if (class_exists($fieldClass)) {
                $fields[$name] = $fieldClass;
            } else {
                $fields[$name] = [
                    'type' => Type::string(),
                    'description' => 'The '.$name.' of the channel',
                    'resolve' => function ($item) use ($name) {
                        $fields = $item->fields;
                        return isset($fields->{$name}) ? $fields->{$name}:null;
                    }
                ];
            }
        }
        
        if (!isset($fields['name'])) {
        }
        
        return $fields;
    }
    
    public function interfaces()
    {
        return [
            GraphQL::type('ChannelFieldsInterface')
        ];
    }
}
