<?php namespace Manivelle\GraphQL\Type;

use GraphQL;
use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\Type as GraphQLType;

use Panneau\Fields\Text;
use Panneau\Fields\MetadataString;

class BubbleFieldsType extends GraphQLType
{
    protected $bubbleType;

    public function attributes()
    {
        $name = $this->bubbleType ? ('Bubble'.studly_case($this->bubbleType->type).'Fields'):'BubbleFields';
        return [
            'name' => $name,
            'description' => 'Bubble fields'
        ];
    }

    public function setBubbleType($bubbleType)
    {
        $this->bubbleType = $bubbleType;
    }

    public function fields()
    {
        $fields = [];
        if ($this->bubbleType) {
            $bubbleTypeFields = $this->bubbleType->getFields();
            foreach ($bubbleTypeFields as $field) {
                $name = $field->name;
                $type = $field->type;
                $label = $field->label;

                $fields[$name] = [
                    'type' => GraphQL::type('BubbleFieldInterface'),
                    'description' => 'The '.$name.' of the bubble',
                    'resolve' => function ($item) use ($name, $type, $label, $field) {
                        //$fields = $item->fields;
                        //$value = isset($fields->{$name}) ? $fields->{$name}:null;
                        $value = array_get($item, 'fields.'.$name, null);
                        return $value ? [
                            'type' => $type,
                            'label' => $label,
                            'value' => $value,
                            'field' => $field
                        ]:null;
                    }
                ];
            }
        }

        return $fields;
    }

    public function interfaces()
    {
        return [
            GraphQL::type('BubbleFieldsInterface')
        ];
    }
}
