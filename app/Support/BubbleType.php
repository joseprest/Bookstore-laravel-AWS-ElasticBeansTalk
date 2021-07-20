<?php namespace Manivelle\Support;

use Panneau\Bubbles\Support\BubbleType as BaseBubbleType;

use Panneau;
use Manivelle\Models\Bubble;
use Manivelle\Models\Fields\Field as FieldModel;
use DB;
use Illuminate\Support\Str;

class BubbleType extends BaseBubbleType
{

    protected $filters = [];
    protected $tokens = [];
    protected $fieldValues = [];

    public function getFilters()
    {
        $filters = array_merge($this->filters, $this->filters());
        return $filters;
    }

    public function snippet()
    {
        $snippet = parent::snippet();

        $snippet['type'] = function () {
            return $this->label;
        };

        return $snippet;
    }

    public function filters()
    {
        return [];
    }

    public function suggestions()
    {
        return [];
    }

    public function fields()
    {
        return [
            [
                'name' => 'updated_at',
                'type' => 'datetime',
                'namespace' => 'fields',
                'hidden' => true,
            ]
        ];
    }

    public function getSuggestionsForBubble($bubble)
    {

        $suggestions = $this->suggestions();
        $filters = $this->getFilters();

        $ids = [];

        $params = [
            'type' => $this->type,
            'not_id' => $bubble->id
        ];

        $type = $this->type;
        $resource = Panneau::resource('bubbles');
        // $query = $resource->query($params);

        $fields = $bubble->fields;
        $unionQuery = null;
        foreach ($suggestions as $suggestion) {
            $query = $resource->query($params)->select('bubbles.id');

            $hasQuery = false;
            if ($suggestion instanceof Closure) {
                $query->where(function ($query) use ($suggestion, $bubble) {
                    $suggestion($query, $bubble);
                });
                $hasQuery = true;
            } else {
                $filter = array_first($filters, function ($key, $item) use ($suggestion) {
                    return $item['name'] === $suggestion;
                });
                if ($filter) {
                    $value = $filter['value']($bubble, $fields);
                    if (!empty($value)) {
                        $key = $type.'_'.$filter['name'];
                        $resource->scopeFilters($query, [
                            $key => $value
                        ], 'where');
                        $hasQuery = true;
                    }
                }
            }

            if (!$hasQuery) {
                continue;
            }

            if (!$unionQuery) {
                $unionQuery = $query;
            } else {
                $unionQuery->union($query);
            }
        }

        return $unionQuery ? $unionQuery->groupBy('bubbles.id')->lists('bubbles.id')->unique()->slice(0, 5):[];

        /*$query->where(function ($query) use ($resource, $suggestions, $filters, $type, $bubble) {
            $fields = $bubble->fields;

            $filterParams = [];
            foreach ($suggestions as $suggestion) {
                if ($suggestion instanceof Closure) {
                    $query->orWhere(function ($query) use ($suggestion, $bubble) {
                        $suggestion($query, $bubble);
                    });
                } else {
                    $filter = array_first($filters, function ($key, $item) use ($suggestion) {
                        return $item['name'] === $suggestion;
                    });

                    if ($filter) {
                        $value = $filter['value']($bubble, $fields);
                        if ($value) {
                            $key = $type.'_'.$filter['name'];
                            $filterParams[$key] = $value;
                        }
                    }
                }
            }

            if (sizeof($filterParams)) {
                $resource->scopeFilters($query, $filterParams, 'orWhere');
            }
        });

        return $query->lists('bubbles.id');*/
    }

    public function getTokens($name, $params = [], $opts = [])
    {
        $fields = $this->getFields();

        $field = array_first($fields, function ($key, $value) use ($name) {
            return $value['name'] === $name;
        });

        if (!$field) {
            return null;
        }

        $field->name = $name;

        $hasMany = $field->getHasMany();
        if ($hasMany) {
            $manyField = app($hasMany);
            $relation = $manyField->getRelation();
        } else {
            $relation = $field->getRelation();
        }

        $tokens = [];
        if (!$relation || $relation === 'metadatas') {
            $tokens = $this->getMetadatasFieldTokens($field, $params, $opts);
        } else {
            $bubbleModel = new Bubble();
            if (method_exists($bubbleModel, $relation)) {
                $relationModel = $bubbleModel->{$relation}()->getModel();
                if ($relationModel instanceof FieldModel) {
                    $tokens = $relationModel::getTokens($name, [
                        'namespace' => Bubble::class.'\\'.$this->type
                    ]);
                }
            }
        }

        return $tokens;
    }

    public function getValues($name, $params = [], $opts = [])
    {
        if (isset($this->fieldValues[$name])) {
            return $this->fieldValues[$name];
        }

        $params = array_merge([
            'type' => $this->type
        ], $params);

        $fields = $this->getFields();

        $field = array_first($fields, function ($key, $value) use ($name) {
            return $value['name'] === $name;
        });

        if (!$field) {
            return null;
        }

        $field->name = $name;

        $hasMany = $field->getHasMany();
        if ($hasMany) {
            $manyField = app($hasMany);
            $relation = $manyField->getRelation();
        } else {
            $relation = $field->getRelation();
        }

        $values = [];
        if (!$relation || $relation === 'metadatas') {
            $values = $this->getMetadatasFieldValues($field, $params, $opts);
        } elseif ($relation) {
            $bubbleModel = new Bubble();
            if (method_exists($bubbleModel, $relation)) {
                $relationModel = $bubbleModel->{$relation}()->getModel();
                if ($relationModel instanceof FieldModel) {
                    $values = $relationModel::getValues($name, [
                        'namespace' => Bubble::class.'\\'.$this->type
                    ]);
                }
            }
        }

        $this->fieldValues[$name] = $values;

        return $values;
    }

    protected function getMetadatasFieldTokens($field, $params = [], $opts = [])
    {
        $tokenFields = array_get($opts, 'fields', $field->tokenFields ? $field->tokenFields:[]);
        $opts['fields'] = $tokenFields;
        $values = $this->getMetadatasFieldValues($field, $params, $opts);

        $tokens = [];
        foreach ($values as $value) {
            $tokenLabel = is_array($value) ? array_get($value, array_get($tokenFields, 'label', 'id'), ''):$value;
            $tokenValue = is_array($value) ? array_get($value, array_get($tokenFields, 'value', 'name'), ''):$value;
            if (empty($tokenValue) || empty($tokenLabel)) {
                continue;
            }
            $tokens[] = [
                'label' => $tokenLabel,
                'value' => $tokenValue,
            ];
        }

        usort($tokens, function ($a, $b) {
            return strcmp($a['label'], $b['label']);
        });

        return $tokens;
    }

    public function getMetadatasFieldValues($field, $params = [], $opts = [])
    {
        $fields = array_get($opts, 'fields', []);
        $subFields = sizeof($fields) ? array_values((array)$fields):array_keys($field->getFields());

        $countPerPage = isset($params['search']) ? 20:50000;
        $loadAllPages = isset($params['search']) ? false:true;
        $page = 1;
        $valuesMap = [];
        do {
            /**
             * Query pages
             */
            $opts['count'] = $countPerPage;
            $opts['page'] = $page;
            $opts['subFields'] = $subFields;
            $items = $this->getMetadatasFieldValuesPage($field, $params, $opts);
            $itemsCount = sizeof($items);

            /**
             * Build values map
             */
            foreach ($items as $item) {
                $key = $item->metadatable_id;

                if (empty($item->metadatable_position)) {
                    continue;
                }

                $value = $item->value;

                if (sizeof($subFields)) {
                    if (preg_match('/\[[0-9]+\]\[[^\]]+\]$/', $item->metadatable_position)) {
                        $key .= preg_replace('/\[[^\]]+\]$/', '', $item->metadatable_position);
                    }

                    if (!isset($valuesMap[$key])) {
                        $valuesMap[$key] = [];
                    }
                    foreach ($subFields as $subFieldKey) {
                        $subFieldName = $field->name.'['.$subFieldKey.']';
                        if (substr($item->name, 0, strlen($subFieldName)) === $subFieldName) {
                            $valueKey = preg_replace('/^'.$field->name.'\[/', '', $item->name);
                            $valueKey = preg_replace('/\[/', '.', $valueKey);
                            $valueKey = preg_replace('/\]/', '', $valueKey);
                            array_set($valuesMap[$key], $valueKey, $value);
                        }
                    }
                } elseif ($item->name === $field->name) {
                    $valuesMap[$key] = $value;
                }
            }

            unset($items);

            $page++;
        } while ($loadAllPages && $itemsCount === $countPerPage);

        $values = [];
        foreach ($valuesMap as $value) {
            if (in_array($value, $values)) {
                continue;
            }

            $values[] = $value;
        }

        return $values;
    }

    protected function getMetadatasFieldValuesPage($field, $params = [], $opts = [])
    {
        $subFields = array_get($opts, 'subFields', []);
        $count = array_get($opts, 'count', 50000);
        $page = array_get($opts, 'page', 1);

        /**
         * Create query
         */
        $query = $this->queryFieldValues($field, $params, $opts)
            ->orderBy('mediatheque_metadatas.id', 'asc');
        if ($count) {
            $query->skip(($page-1) * $count)
                ->take($count);
        }
        $items = $query->get();

        /**
         * Find other items
         */
        if ((isset($params['search']) || isset($params['value'])) &&
            sizeof($items)
        ) {
            $firstPosition = $items[0]->metadatable_position;
            if (preg_match('/([^\[]+(\[[0-9]+\])?)(.*)?/', $firstPosition, $matches)) {
                $queryParams = [];
                foreach ($items as $item) {
                    if (preg_match('/([^\[]+(\[[0-9]+\])?)(.*)?/', $item->metadatable_position, $matches)) {
                        $queryParams[] = [
                            'position' => $matches[1].(isset($matches[3]) && !empty($matches[3]) ? '[%':''),
                            'metadatable_id' => $item->metadatable_id
                        ];
                    }
                }

                $query = $this->newMetadataQuery();
                $this->queryFieldName($query, $field, $subFields);

                $query->where(function ($query) use ($queryParams) {
                    foreach ($queryParams as $param) {
                        $query->orWhere(function ($query) use ($param) {
                            $query->where('mediatheque_metadatables.metadatable_position', 'LIKE', $param['position']);
                            $query->where('mediatheque_metadatables.metadatable_id', $param['metadatable_id']);
                        });
                    }
                });

                $items = $query->get();
            }
        }

        return $items;
    }

    protected function queryFieldValues($field, $params = [], $opts = [])
    {
        $subFields = array_get($opts, 'subFields', []);

        $query = $this->newMetadataQuery();

        $query->leftJoin('bubbles', function ($join) use ($params) {
            $join->on('mediatheque_metadatables.metadatable_id', '=', 'bubbles.id');
        });
        $query->whereNotNull('bubbles.id');
        if (isset($params['type'])) {
            $query->where('bubbles.type', $params['type']);
        } else {
            $query->where('bubbles.type', $this->type);
        }

        if (!isset($params['search']) && !isset($params['value'])) {
            $this->queryFieldName($query, $field, $subFields);
        }

        if (isset($params['search'])) {
            $value = $params['search'];
            $searchFields = array_get($opts, 'searchFields', $field->tokenSearchFields ? $field->tokenSearchFields:$subFields);
            $this->queryFieldName($query, $field, $searchFields, 'mediatheque_metadatas');
            $this->querySearch($query, $value, 'mediatheque_metadatas');
        }

        if (isset($params['value'])) {
            $value = (array)$params['value'];
            $valueFields = (array)array_get($opts, 'fields.value', []);
            $query->join('mediatheque_metadatables as mt2', function ($join) {
                $join->on('mt2.metadatable_id', '=', 'mediatheque_metadatables.metadatable_id');
                $join->on('mt2.metadatable_type', '=', 'mediatheque_metadatables.metadatable_type');
            });
            $query->join('mediatheque_metadatas as mt3', 'mt3.id', '=', 'mt2.metadata_id');
            $this->queryFieldName($query, $field, $valueFields, 'mt3');
            $this->querySearch($query, $value, 'mt3');
        }

        if (isset($params['channel_id'])) {
            $query->leftJoin('channels_bubbles_pivot', function ($join) use ($params) {
                $join->on('bubbles.id', '=', 'channels_bubbles_pivot.bubble_id');
                $join->on('channels_bubbles_pivot.channel_id', '=', DB::raw('"'.$params['channel_id'].'"'));
            });
        }

        return $query;
    }

    public function getFieldTokens($field, $params = [], $opts = [])
    {
        $tokenFields = array_get($opts, 'fields', $field->tokenFields ? $field->tokenFields:[]);
        $subFields = sizeof($tokenFields) ? array_values((array)$tokenFields):array_keys($field->getFields());

        $query = $this->newMetadataQuery();

        $query->leftJoin('bubbles', function ($join) use ($params) {
            $join->on('mediatheque_metadatables.metadatable_id', '=', 'bubbles.id');
            if (isset($params['type'])) {
                $join->on('bubbles.type', '=', DB::raw('"'.$params['type'].'"'));
            }
        });

        if (!isset($params['search']) && !isset($params['value'])) {
            $this->queryFieldName($query, $field, $subFields);
        }

        if (isset($params['search'])) {
            $value = $params['search'];
            $searchFields = array_get($opts, 'searchFields', $field->tokenSearchFields ? $field->tokenSearchFields:$subFields);
            /*$query->whereExists(function($query) use ($field, $searchFields, $value)
            {
                $query->select(DB::raw(1))
                        ->from('mediatheque_metadatas as mt2')
                        ->leftJoin('mediatheque_metadatables as mt3', 'mt2.id', '=', 'mt3.metadata_id')
                        ->whereRaw('mt3.metadatable_id = mediatheque_metadatables.metadatable_id');
                $this->queryFieldName($query, $field, $searchFields, 'mt2');
                $this->querySearch($query, $value, 'mt2');
            });*/

            /*$query->join('mediatheque_metadatables as mt2', function($join)
            {
                $join->on('mt2.metadatable_id', '=', 'mediatheque_metadatables.metadatable_id');
                $join->on('mt2.metadatable_type', '=', 'mediatheque_metadatables.metadatable_type');
            });
            $query->join('mediatheque_metadatas as mt3', 'mt3.id', '=', 'mt2.metadata_id');
            $this->queryFieldName($query, $field, $searchFields, 'mt3');
            $this->querySearch($query, $value, 'mt3');*/

            $this->queryFieldName($query, $field, $searchFields, 'mediatheque_metadatas');
            $this->querySearch($query, $value, 'mediatheque_metadatas');
            $query->skip(0)->take(20);
        }

        if (isset($params['value'])) {
            $value = (array)$params['value'];
            $valueFields = (array)array_get($tokenFields, 'value', []);
            /*$query->whereExists(function($query) use ($field, $valueFields, $value)
            {
                $query->select(DB::raw(1))
                        ->from('mediatheque_metadatas as mt2')
                        ->leftJoin('mediatheque_metadatables as mt3', 'mt2.id', '=', 'mt3.metadata_id')
                        ->whereRaw('mt3.metadatable_id = mediatheque_metadatables.metadatable_id');
                $this->queryFieldName($query, $field, $valueFields, 'mt2');
                $this->queryValue($query, $value, 'mt2');
            });*/

            $query->join('mediatheque_metadatables as mt2', function ($join) {
                $join->on('mt2.metadatable_id', '=', 'mediatheque_metadatables.metadatable_id');
                $join->on('mt2.metadatable_type', '=', 'mediatheque_metadatables.metadatable_type');
            });
            $query->join('mediatheque_metadatas as mt3', 'mt3.id', '=', 'mt2.metadata_id');
            $this->queryFieldName($query, $field, $valueFields, 'mt3');
            $this->querySearch($query, $value, 'mt3');
        }

        if (isset($params['channel_id'])) {
            $query->leftJoin('channels_bubbles_pivot', function ($join) use ($params) {
                $join->on('bubbles.id', '=', 'channels_bubbles_pivot.bubble_id');
                $join->on('channels_bubbles_pivot.channel_id', '=', DB::raw('"'.$params['channel_id'].'"'));
            });
        }

        $items = $query->get();

        /**
         * Find other items
         */
        if ((isset($params['search']) || isset($params['value'])) &&
            sizeof($items)
        ) {
            $firstPosition = $items[0]->metadatable_position;
            if (preg_match('/([^\[]+(\[[0-9]+\])?)(.*)?/', $firstPosition, $matches)) {
                $queryParams = [];
                foreach ($items as $item) {
                    if (preg_match('/([^\[]+(\[[0-9]+\])?)(.*)?/', $item->metadatable_position, $matches)) {
                        $queryParams[] = [
                            'position' => $matches[1].(isset($matches[3]) && !empty($matches[3]) ? '[%':''),
                            'metadatable_id' => $item->metadatable_id
                        ];
                    }
                }

                $query = $this->newMetadataQuery();
                $this->queryFieldName($query, $field, $subFields);

                $query->where(function ($query) use ($queryParams) {
                    foreach ($queryParams as $param) {
                        $query->orWhere(function ($query) use ($param) {
                            $query->where('mediatheque_metadatables.metadatable_position', 'LIKE', $param['position']);
                            $query->where('mediatheque_metadatables.metadatable_id', $param['metadatable_id']);
                        });
                    }
                });

                $items = $query->get();
            }
        }

        $tokensMap = [];
        foreach ($items as $item) {
            $key = $item->metadatable_id;

            $type = $item->type;
            $value = isset($item->{'value_'.$type}) ? $item->{'value_'.$type}:$item->value;

            if (sizeof($subFields)) {
                if (!isset($tokensMap[$key])) {
                    $tokensMap[$key] = [];
                }

                foreach ($subFields as $subFieldKey) {
                    $subFieldName = $field->name.'['.$subFieldKey.']';
                    if (substr($item->name, 0, strlen($subFieldName)) === $subFieldName) {
                        $valueKey = preg_replace('/^'.$field->name.'\[/', '', $item->name);
                        $valueKey = preg_replace('/\[/', '.', $valueKey);
                        $valueKey = preg_replace('/\]/', '', $valueKey);
                        if (!is_array($tokenFields) && $valueKey === $tokenFields) {
                            array_set($tokensMap[$key], 'value', Str::slug($value));
                            array_set($tokensMap[$key], 'label', $value);
                        } else {
                            $tokenKey = array_search($valueKey, $tokenFields);
                            array_set($tokensMap[$key], $tokenKey !== false ? $tokenKey:$valueKey, $value);
                        }
                    }
                }
            } elseif ($item->name === $field->name) {
                $tokensMap[$key] = [
                    'label' => $value,
                    'value' => $value
                ];
            }
        }

        $values = [];
        $tokens = [];
        foreach ($tokensMap as $token) {
            $value = array_get($token, 'value', $token);
            if (!in_array($value, $values)) {
                $tokens[] = $token;
                $values[] = $value;
            }
        }

        usort($tokens, function ($a, $b) {
            $labelA = is_array($a) ? array_get($a, 'label', ''):$a;
            $labelB = is_array($b) ? array_get($b, 'label', ''):$b;
            return strcmp($labelA, $labelB);
        });

        return $tokens;
    }

    public function newMetadataQuery()
    {
        $types = ['date', 'time', 'datetime', 'float', 'boolean'];
        $lastIf = null;
        foreach ($types as $type) {
            if (!$lastIf) {
                $lastIf = 'IF(mediatheque_metadatas.type = "'.$type.'", mediatheque_metadatas.value_'.$type.', mediatheque_metadatas.value)';
            } else {
                $lastIf = 'IF(mediatheque_metadatas.type = "'.$type.'", mediatheque_metadatas.value_'.$type.', '.$lastIf.')';
            }
        }

        $query = DB::table('mediatheque_metadatas')
                    ->select(
                        'mediatheque_metadatas.name',
                        DB::raw($lastIf.' as value'),
                        /*'mediatheque_metadatas.type',
                        'mediatheque_metadatas.value',
                        'mediatheque_metadatas.value_date',
                        'mediatheque_metadatas.value_time',
                        'mediatheque_metadatas.value_datetime',
                        'mediatheque_metadatas.value_boolean',
                        'mediatheque_metadatas.value_float',*/
                        'mediatheque_metadatables.metadatable_id',
                        'mediatheque_metadatables.metadatable_position'
                    );

        $query->leftJoin('mediatheque_metadatables', 'mediatheque_metadatas.id', '=', 'mediatheque_metadatables.metadata_id');
        $query->whereNotNull('mediatheque_metadatables.id');

        return $query;
    }

    public function queryFieldName($query, $field, $subFields, $table = 'mediatheque_metadatas')
    {
        $query->where(function ($query) use ($field, $subFields, $table) {
            $fieldsNames = [];
            if (sizeof($subFields)) {
                foreach ($subFields as $subFieldKey) {
                    $fieldsNames[] = $field->name.'['.$subFieldKey.']';
                }
            } else {
                $fieldsNames[] = $field->name;
            }

            $query->orWhereIn($table.'.name', $fieldsNames);
            foreach ($fieldsNames as $name) {
                $query->orWhere($table.'.name', 'LIKE', $name.'[%');
            }
        });
    }

    public function queryValue($query, $value, $table = 'mediatheque_metadatas')
    {
        $query->where(function ($query) use ($value, $table) {
            $types = ['date', 'time', 'datetime', 'boolean', 'float'];
            foreach ($types as $type) {
                $query->orWhere(function ($query) use ($type, $value, $table) {
                    $query->where($table.'.type', $type);
                    $query->where($table.'.value_'.$type, $value);
                });
            }
            $query->orWhere(function ($query) use ($types, $value, $table) {
                $query->whereNotIn($table.'.type', $types);
                $query->where(function ($query) use ($table, $value) {
                    foreach ($value as $search) {
                        $query->orWhere($table.'.value', 'LIKE', '%'.$search.'%');
                    }
                });
            });
        });
    }

    public function querySearch($query, $search, $table = 'mediatheque_metadatas')
    {
        if (is_array($search)) {
            $query->where(function ($query) use ($table, $search) {
                foreach ($search as $text) {
                    if (!empty($text)) {
                        $query->orWhere($table.'.value', 'LIKE', '%'.$text.'%');
                    }
                }
            });
        } else {
            $query->where($table.'.value', 'LIKE', '%'.$search.'%');
        }
    }

    /**
     * Email
     */
    public function getEmailLayout()
    {
        return 'normal';
    }

    public function getEmailFields()
    {
        return [];
    }

    public function getEmailTexts()
    {
        return [
            'footer' => trans('share.email.footer'),
        ];
    }

    public function getEmailTopButton($url)
    {
        return null;
    }

    public function getEmailBottomButton($url)
    {
        return null;
    }
}
