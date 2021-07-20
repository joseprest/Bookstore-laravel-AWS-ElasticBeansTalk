<?php namespace Manivelle\GraphQL\Type;

use GraphQL;
use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\Type as GraphQLType;

class ConditionFieldsType extends GraphQLType
{
    protected $conditionType;
    
    public function attributes()
    {
        return [
            'name' => 'ConditionFields',
            'description' => 'Channel fields'
        ];
    }
    
    public function fields()
    {
        $fields = [];
        $conditionType = $this->getConditionType();
        $typeFields = $conditionType->getFields();
        foreach ($typeFields as $field) {
            $name = $field->name;
            $type = $field->graphql_type;
            
            $fields[$name] = [
                'type' => isset($type) ? $type:Type::string(),
                'description' => 'The '.$name.' of the channel',
                'resolve' => function ($fields) use ($name) {
                    return $fields->{$name};
                }
            ];
        }
        
        return $fields;
    }
    
    public function getConditionType()
    {
        if ($this->conditionType) {
            return $this->conditionType;
        }
        
        $this->conditionType = app('\Manivelle\Support\ConditionType');
        
        return $this->conditionType;
    }
}
