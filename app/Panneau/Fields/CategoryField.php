<?php namespace Manivelle\Panneau\Fields;

use Panneau\Support\Field;

use Illuminate\Support\Str;
use Illuminate\Support\Fluent;

use Manivelle\Models\Fields\Category as CategoryModel;

class CategoryField extends Field
{

    protected $attributes = [
        'type' => 'category',
        'tokenFields' => [
            'value' => 'id',
            'label' => 'name'
        ],
        'tokenSearchFields' => [
            'name'
        ]
    ];

    /*protected $fields = [
        'id' => \Panneau\Fields\MetadataString::class,
        'name' => \Panneau\Fields\MetadataString::class
    ];*/


    protected $relation = 'fields_categories';

    protected function saveToRelation($relation, $data, $pivotData = array(), $fieldName = null)
    {
        if (empty($data)) {
            $data = null;
        }
        $pivotData['field_name'] = preg_replace('/\[[0-9]+\]/', '', $this->name);
        parent::saveToRelation($relation, $data, $pivotData, $fieldName);
    }

    protected function saveFieldsCategoriesRelation($existing, $data, $pivotData, $fieldName)
    {
        //Build the model data
        $nameId = Str::slug(array_get($data, 'name'));
        $modelData = array_except($data, ['id']);
        $modelData['external_id'] = array_get($data, 'id', $nameId);

        //Cleanup null values
        $modelDataNotNull = [];
        foreach ($modelData as $key => $value) {
            if ($value !== null) {
                $modelDataNotNull[$key] = $value;
            }
        }
        $modelData = $modelDataNotNull;

        //Get namespace
        $namespace = get_class($this->model);
        if (isset($this->model) && isset($this->model->type)) {
            $namespace .= '\\'.$this->model->type;
        }

        // If there is no existing model or the id doesn't match, try to find
        // an external item
        if (!$existing || $existing->external_id !== $modelData['external_id']) {
            $existing = CategoryModel::where('external_id', $modelData['external_id'])
                ->where('namespace', $namespace)
                ->first();
        }

        //If nothing is find, create a new item
        if (!$existing) {
            $existing = new CategoryModel();
        }

        //Update the item
        $existing->namespace = $namespace;
        $existing->fill($modelData);
        $existing->save();

        return $existing;
    }

    protected function getValueFromFieldsCategoriesRelation($item, $name, $fieldName)
    {
        $category = new Fluent([
            'id' => $item->external_id,
            'name' => $item->name
        ]);


        return $category;
    }
}
