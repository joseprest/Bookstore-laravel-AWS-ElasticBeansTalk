<?php namespace Manivelle\Panneau\Fields;

use Panneau\Support\Field;

use Illuminate\Support\Str;
use Illuminate\Support\Fluent;

use Manivelle\Models\Fields\Person as PersonModel;

class PersonField extends Field
{

    protected $attributes = [
        'type' => 'person'
    ];

    /*protected $fields = [
        'firstname' => \Panneau\Fields\MetadataString::class,
        'lastname' => \Panneau\Fields\MetadataString::class,
        'name' => \Panneau\Fields\MetadataString::class,
        'id' => \Panneau\Fields\MetadataString::class
    ];*/

    protected $relation = 'fields_persons';

    protected function saveToRelation($relation, $data, $pivotData = array(), $fieldName = null)
    {
        $pivotData['field_name'] = preg_replace('/\[[0-9]+\]/', '', $this->name);
        parent::saveToRelation($relation, $data, $pivotData, $fieldName);
    }

    protected function saveFieldsPersonsRelation($existing, $data, $pivotData, $fieldName)
    {
        //Build a unique id from name
        $firstname = array_get($data, 'firstname', '');
        $lastname = array_get($data, 'lastname', '');
        $name = [];
        if (!empty($firstname)) {
            $name[] = $firstname;
        }
        if (!empty($lastname)) {
            $name[] = $lastname;
        }
        $name = implode(' ', $name);
        $nameId = Str::slug(array_get($data, 'name', $name));

        //Build the model data
        $modelData = array_except($data, ['id']);
        $modelData['external_id'] = array_get($data, 'id', $nameId);
        $modelData['order'] = array_get($data, 'id', '');

        //Cleanup null values
        $modelDataNotNull = [];
        foreach ($modelData as $key => $value) {
            if ($value !== null) {
                $modelDataNotNull[$key] = $value;
            }
        }
        $modelData = $modelDataNotNull;

        //If there is no ordering, build one from lastname and firstname
        if (!isset($modelData['order']) || empty($modelData['order'])) {
            $modelData['order'] = Str::slug($lastname.' '.$firstname);
        }

        //Get namespace
        $namespace = get_class($this->model);
        if (isset($this->model) && isset($this->model->type)) {
            $namespace .= '\\'.$this->model->type;
        }

        // If there is no existing model or the id doesn't match, try to find
        // an external item
        if (!$existing || $existing->external_id !== $modelData['external_id']) {
            $existing = PersonModel::where('external_id', $modelData['external_id'])
                ->where('namespace', $namespace)
                ->first();
        }

        //If nothing is find, create a new item
        if (!$existing) {
            $existing = new PersonModel();
        }

        //Update the item
        $existing->namespace = $namespace;
        $existing->fill($modelData);
        $existing->save();

        return $existing;
    }

    protected function getValueFromFieldsPersonsRelation($item, $name, $fieldName)
    {
        $category = new Fluent([
            'id' => $item->order,
            'firstname' => $item->firstname,
            'lastname' => $item->lastname,
            'name' => $item->name
        ]);


        return $category;
    }
}
