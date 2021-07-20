<?php namespace Manivelle\Models;

use Illuminate\Database\Eloquent\Model;
use Manivelle\Models\Traits\LoadTrait;

abstract class Pivot extends Model
{
    use LoadTrait;

    abstract public function getPivotRelationName();
    
    /**
     * Get the pivot relation model
     *
     * @return Illuminate\Database\Eloquent\Model The pivot related model
     */
    public function getPivotRelation()
    {
        $name = $this->getPivotRelationName();
        $key = $this->getKeyName();
        return isset($this->attributes[$key]) ? $this->{$name}:null;
    }
    
    /**
     * Get the pivot as array, which merge the related model and the current pivot model.
     *
     * @return array The pivot as array
     */
    public function toArray()
    {
        $data = parent::toArray();
        $item = $this->getPivotRelation();
        $itemData = $item ? $item->toArray():[];
        
        return array_merge($itemData, $data, [
            'id' => array_get($itemData, 'id')
        ]);
    }

    /**
     * Determine if an attribute exists on the model.
     *
     * @param  string  $key
     * @return bool
     */
    public function __isset($key)
    {
        $item = $this->getPivotRelation();
        return (isset($this->attributes[$key]) || isset($this->relations[$key])) ||
                ($this->hasGetMutator($key) && ! is_null($this->getAttributeValue($key)) ||
                ($item && isset($item->{$key})));
    }

    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        $value = $this->getAttribute($key);
        if (($key === 'id' || $value === null) && $item = $this->getPivotRelation()) {
            return $item->getAttribute($key);
        }
        
        return $value;
    }

    /**
     * Dynamically call method on the parent.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        $item = $this->getPivotRelation();
        if ($item) {
            return call_user_func_array([$item, $method], $parameters);
        }
        
        $name = $this->getPivotRelationName();
        $relation = $this->$name()->getModel();
        if (method_exists($relation, $method)) {
            return call_user_func_array([$relation, $method], $parameters);
        }
        
        return parent::__call($method, $parameters);
    }
}
