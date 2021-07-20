<?php namespace Manivelle\Panneau\Fields;

use Panneau\Support\Field;

use Illuminate\Support\Str;
use Illuminate\Support\Fluent;

use Manivelle\Models\Fields\Location as LocationModel;

class LocationField extends Field
{
    protected $attributes = array(
        'type' => 'location',
        'tokenFields' => [
            'value' => 'id',
            'label' => 'name'
        ],
        'tokenSearchFields' => [
            'name'/*,
            'adress',
            'city',
            'region'*/
        ]
    );

    /*protected $fields = [
        'id' => \Panneau\Fields\MetadataString::class,
        'name' => \Panneau\Fields\MetadataString::class,
        'address' => \Panneau\Fields\MetadataString::class,
        'postalcode' => \Panneau\Fields\MetadataString::class,
        'city' => \Panneau\Fields\MetadataString::class,
        'region' => \Panneau\Fields\MetadataString::class,
        'country' => \Panneau\Fields\MetadataString::class,
        'position' => \Manivelle\Panneau\Fields\PositionField::class
    ];*/

    protected $relation = 'fields_locations';

    protected function saveToRelation($relation, $data, $pivotData = array(), $fieldName = null)
    {
        $pivotData['field_name'] = preg_replace('/\[[0-9]+\]/', '', $this->name);
        parent::saveToRelation($relation, $data, $pivotData, $fieldName);
    }

    protected function saveFieldsLocationsRelation($existing, $data, $pivotData, $fieldName)
    {
        if (isset($this->name_from) && (!isset($data['name']) || empty($data['name']))) {
            $data['name'] = array_get($this->model, $this->name_from);
        }
        //Build the model data
        $nameId = Str::slug(array_get($data, 'name'));
        $modelData = array_except($data, ['id', 'position']);
        $modelData['external_id'] = array_get($data, 'id', $nameId);
        $modelData['latitude'] = array_get($data, 'position.latitude', 0);
        $modelData['longitude'] = array_get($data, 'position.longitude', 0);

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
            $existing = LocationModel::where('external_id', $modelData['external_id'])
                ->where('namespace', $namespace)
                ->first();
        }

        //If nothing is find, create a new item
        if (!$existing) {
            $existing = new LocationModel();
        }

        //Update the item
        $existing->namespace = $namespace;
        $existing->fill($modelData);
        $existing->save();

        return $existing;
    }

    protected function getValueFromFieldsLocationsRelation($item, $name, $fieldName)
    {
        $position = new Fluent([
            'latitude' => $item->latitude,
            'longitude' => $item->longitude
        ]);
        $location = new Fluent([
            'id' => $item->external_id,
            'name' => $item->name,
            'address' => $item->address,
            'postalcode' => $item->postalcode,
            'city' => $item->city,
            'region' => $item->region,
            'country' => $item->country,
            'position' => $position
        ]);


        return $location;
    }
}
