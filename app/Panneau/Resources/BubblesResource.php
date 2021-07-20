<?php namespace Manivelle\Panneau\Resources;

use Manivelle;
use Panneau\Support\EloquentResource;
use Panneau;
use DB;
use Manivelle\Models\Screen;
use Manivelle\Models\ScreenChannel;
use Manivelle\Models\Playlist;
use Manivelle\Models\Bubble;

class BubblesResource extends EloquentResource
{
    protected $modelName = 'Manivelle\Models\Bubble';

    protected function buildQuery($query, $params = [])
    {
        if (!$params) {
            return;
        }

        /**
         * Apply filters
         */
        $filterParams = [];
        foreach ($params as $key => $value) {
            if (!preg_match('/^filter\_([^\_]+)\_(.*)$/', $key, $matches)) {
                continue;
            }

            $bubbleType = $matches[1];
            $fieldName = $matches[2];
            $filterParams[$bubbleType . '_' . $fieldName] = $value;
        }

        if (sizeof($filterParams)) {
            $this->scopeFilters($query, $filterParams);
        }
    }

    public function scopeFilters($query, $params, $where = 'where')
    {
        $metadatasQueries = [];
        $filtersByBubbleType = [];

        foreach ($params as $key => $value) {
            $keyParts = explode('_', $key, 2);
            if (sizeof($keyParts) < 2) {
                continue;
            }
            $bubbleType = $keyParts[0];
            $fieldName = $keyParts[1];

            if (!isset($filtersByBubbleType[$bubbleType])) {
                $filters = Manivelle::bubbleType($bubbleType)->getFilters();
                $filtersByBubbleType[$bubbleType] = $filters;
            } else {
                $filters = $filtersByBubbleType[$bubbleType];
            }

            $filtersByMetadatas = array_where($filters, function ($key, $filter) use ($fieldName) {
                return $filter['name'] === $fieldName && isset($filter['queryScopeMetadata']);
            });

            $filtersByScope = array_where($filters, function ($key, $filter) use ($fieldName) {
                return $filter['name'] === $fieldName &&
                    isset($filter['queryScope']) &&
                    !isset($filter['queryScopeMetadata']);
            });

            foreach ($filtersByMetadatas as $filter) {
                $type = array_get($filter, 'type');
                $values =
                    $type === 'tokens'
                        ? (!is_array($value)
                            ? explode(',', $value)
                            : (array) $value)
                        : (array) $value;
                $metadatasQueries[] = [
                    'queryScope' => $filter['queryScopeMetadata'],
                    'values' => $values,
                ];
            }

            foreach ($filtersByScope as $filter) {
                $type = array_get($filter, 'type');
                $values =
                    $type === 'tokens'
                        ? (!is_array($value)
                            ? explode(',', $value)
                            : (array) $value)
                        : (array) $value;

                $query->$where(function ($query) use ($filter, $values) {
                    foreach ($values as $value) {
                        $query->orWhere(function ($query) use ($filter, $value) {
                            $filter['queryScope']($query, $value);
                        });
                    }
                });
            }
        }

        if (sizeof($metadatasQueries)) {
            $query->{$where . 'In'}('id', function ($query) use ($metadatasQueries) {
                $query
                    ->select('mediatheque_metadatables.metadatable_id')
                    ->from('mediatheque_metadatables')
                    ->join(
                        'mediatheque_metadatas',
                        'mediatheque_metadatables.metadata_id',
                        '=',
                        'mediatheque_metadatas.id'
                    )
                    ->where('mediatheque_metadatables.metadatable_type', Bubble::class);

                $query->where(function ($query) use ($metadatasQueries) {
                    foreach ($metadatasQueries as $metadataQuery) {
                        $values = $metadataQuery['values'];
                        $queryScope = $metadataQuery['queryScope'];
                        foreach ($values as $value) {
                            $query->orWhere(function ($query) use ($queryScope, $value) {
                                $queryScope($query, $value);
                            });
                        }
                    }
                });
            });
        }
    }

    protected function scopeWithRelations($query, $value)
    {
        $query->with(['channels', 'metadatas', 'texts', 'pictures']);
    }

    protected function scopeNotId($query, $value)
    {
        if (is_array($value)) {
            $query->whereNotIn('bubbles.id', $value);
        } else {
            $query->where('bubbles.id', '!=', $value);
        }
    }

    protected function scopeType($query, $value)
    {
        if (is_array($value)) {
            $query->whereIn('bubbles.type', $value);
        } else {
            $query->where('bubbles.type', $value);
        }
    }

    protected function scopeHandle($query, $value)
    {
        if (is_array($value)) {
            $query->whereIn('handle', $value);
        } else {
            $query->where('handle', $value);
        }
    }

    protected function scopeId($query, $value)
    {
        if (is_array($value)) {
            $query->whereIn('bubbles.id', $value);
        } else {
            $query->where('bubbles.id', $value);
        }
    }

    protected function scopeIds($query, $value)
    {
        $query->whereIn('bubbles.id', $value);
    }

    protected function scopeOrderBy($query, $value)
    {
        if (is_array($value)) {
            $query->orderBy($value[0], $value[1]);
        } else {
            $query->orderBy($value);
        }
    }

    protected function scopePlaylistId($query, $value)
    {
        $query->whereHas('playlists', function ($query) use ($value) {
            if (is_array($value)) {
                $query->whereIn('playlists.id', $value);
            } else {
                $query->where('playlists.id', $value);
            }
        });
    }

    protected function scopeOrganisationId($query, $value)
    {
        if (is_array($value)) {
            $query->whereIn('organisation_id', $value);
        } else {
            $query->where('organisation_id', $value);
        }
    }

    public function scopeChannelId($query, $value)
    {
        /*$query->join('channels_bubbles_pivot as channels_channel_id', 'channels_channel_id.bubble_id', '=', 'bubbles.id');

        if(is_array($value))
        {
            $query->whereIn('channels_channel_id.channel_id', $value);
        }
        else
        {
            $query->where('channels_channel_id.channel_id', $value);
        }*/

        /*$query->whereHas('channels', function($query) use ($value)
        {
            if(is_array($value))
            {
                $query->whereIn('channels.id', $value);
            }
            else
            {
                $query->where('channels.id', $value);
            }
        });*/

        $query->whereIn('id', function ($query) use ($value) {
            $query->select('channels_bubbles_pivot.bubble_id')->from('channels_bubbles_pivot');

            if (is_array($value)) {
                $query->whereIn('channels_bubbles_pivot.channel_id', $value);
            } else {
                $query->where('channels_bubbles_pivot.channel_id', $value);
            }
        });
    }

    protected function scopeScreenId($query, $value)
    {
        $screen = Screen::where('id', $value)->first();
        if (!$screen) {
            $query->where('bubbles.id', -1);
            return;
        }
        $ids = $screen->getBubbleIds();

        //Convert ids to ranges
        $currentRangeStart = null;
        $lastId = null;
        $idRanges = [];
        $idsOnly = [];
        foreach ($ids as $id) {
            if ($currentRangeStart === null && $lastId !== null) {
                $currentRangeStart = $lastId;
            }

            if ($lastId !== null) {
                if ($id - $lastId !== 1) {
                    if ($currentRangeStart !== $lastId) {
                        $idRanges[] = [$currentRangeStart, $lastId];
                    } else {
                        $idsOnly[] = $lastId;
                    }
                    $currentRangeStart = null;
                }
            }

            $lastId = $id;
        }
        if ($currentRangeStart !== null && $lastId !== null && $currentRangeStart !== $lastId) {
            $idRanges[] = [$currentRangeStart, $lastId];
        } elseif ($lastId !== null) {
            $idsOnly[] = $lastId;
        }

        if (sizeof($idRanges) || sizeof($idsOnly)) {
            $query->where(function ($query) use ($idRanges, $idsOnly) {
                foreach ($idRanges as $idRange) {
                    $query->orWhereBetween('id', $idRange);
                }

                if (sizeof($idsOnly)) {
                    $query->orWhereIn('id', $idsOnly);
                }
            });
        }
    }

    protected function saveModel($model, $data)
    {
        $model->fill($data);
        $dirty = $model->getDirty();

        $return = $model->save();

        if (isset($data['fields'])) {
            $model->saveFields($data['fields']);
            if (!sizeof($dirty)) {
                //$model->touch();
            }
        }

        return $model;
    }

    public function getFieldValues($relationName, $fieldName, $params = [])
    {
        $model = $this->newModel();
        $relation = $model->$relationName();
        $relationTable = $relation->getTable();
        $positionKey = $this->getPositionKey($model, $relationName);
        $positionColumn = $relationTable . '.' . $positionKey;

        //Get items
        $query = $this->query($params);
        $query->setEagerLoads([]);
        $query->with($relationName);
        $query->select('bubbles.id');
        $query->whereHas($relationName, function ($query) use ($positionColumn, $fieldName) {
            $query->where($positionColumn, $fieldName);
        });
        $items = $query->get();

        //Get values
        $values = [];
        foreach ($items as $item) {
            $relations = $item->{$relationName};
            $relationItem = $relations->first(function ($key, $value) use (
                $positionKey,
                $fieldName
            ) {
                return $value->pivot->{$positionKey} === $fieldName;
            });
            if ($relationItem) {
                $values[] = $relationItem->value;
            }
        }

        return $values;
    }

    protected function getPositionKey($model, $relation)
    {
        $morphName = $this->getMorphName($this->model, $relation);
        $positionKey = $morphName . '_position';
        return $positionKey;
    }

    protected function getMorphName($model, $relation)
    {
        $morphType = $model->{$relation}()->getMorphType();
        $morphName = explode('_', $morphType)[0];
        return $morphName;
    }
}
