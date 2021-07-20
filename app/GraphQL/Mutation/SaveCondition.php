<?php namespace Manivelle\GraphQL\Mutation;

use GraphQL;
use Request;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Mutation;

use Manivelle\Models\Screen;
use Manivelle\Models\Organisation;
use Manivelle\Models\Condition;
use Log;

class SaveCondition extends Mutation
{
    protected $attributes = [
        'description' => 'Save a condition to an organisation'
    ];
    
    public function type()
    {
        return GraphQL::type('Condition');
    }
    
    public function args()
    {
        $args = [
            'organisation_id' => [
                'name' => 'organisation_id',
                'type' => Type::string()
            ],
            'condition_id' => [
                'name' => 'condition_id',
                'type' => Type::string()
            ]
        ];
        
        $conditionType = app('\Manivelle\Support\ConditionType');
        $fields = $conditionType->getFields();
        foreach ($fields as $field) {
            $name = $field->name;
            $type = $field->graphql_type;
            $args[$name] = [
                'name' => $name,
                'type' => isset($type) ? $type:Type::string()
            ];
        }
        
        return $args;
    }
    
    public function resolve($root, $args)
    {
        if (isset($args['organisation_id'])) {
            $organisation = Organisation::find($args['organisation_id']);
        } else {
            $organisation = Request::route('organisation');
        }
        
        if (!$organisation) {
            throw new \GraphQL\Error('Organisation not found');
        }
        
        try {
            $condition = isset($args['condition_id']) ? Condition::find($args['condition_id']):new Condition();
            $condition->organisation_id = $organisation->id;
            $condition->save();
            $condition->saveFields(array_except($args, ['organisation_id']));
        } catch (\Exception $e) {
            Log::error($e);
            throw $e;
        }
        
        return $condition;
    }
}
