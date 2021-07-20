<?php namespace Manivelle\Models\Traits;

use Manivelle\Models\Pivot;

trait LoadTrait
{
    public function loadIfNotLoaded($relations)
    {
        $relations = (array)$relations;
        if ($this instanceof Pivot) {
            $notFound = false;
            foreach ($relations as $relation) {
                $relationName = array_get(explode('.', $relation), '0');
                if (!method_exists($this, $relationName)) {
                    $notFound = true;
                    break;
                }
            }
            if ($notFound) {
                $item = $this->getPivotRelation();
                return $item->loadIfNotLoaded($relations);
            }
        }
        
        $relationsToLoad = [];
        foreach ($relations as $relation) {
            if (!$this->relationLoaded($relation)) {
                $relationsToLoad[] = $relation;
            }
        }
        if (sizeof($relationsToLoad)) {
            $this->load($relationsToLoad);
        }
    }
}
