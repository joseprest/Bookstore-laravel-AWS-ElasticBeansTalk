<?php

namespace Manivelle\Cache;

use Manivelle\Models\Bubble;
use Log;
use Cache as CacheFacade;

use Request;

use Manivelle\Cache\Traits\GraphQLRequest;

class BubblePageJson extends Cache
{
    use GraphQLRequest;

    protected $key = 'bubble_page_json';
    protected $cacheOnCloud = true;

    protected function getKeyPrefix()
    {
        $parts = [];
        $namespace = config('manivelle.core.cache_namespace', '');
        if(!empty($namespace))
        {
            $parts[] = $namespace;
        }
        $parts[] = $this->key;
        if(!empty($this->keySuffix))
        {
            $parts[] = $this->keySuffix;
        }
        return implode('_', $parts);
    }

    public function getKey()
    {
        $prefix = $this->getKeyPrefix();

        if($this->item instanceof Bubble)
        {
            $id = $this->item->id;
            $count = config('manivelle.screens.bubbles_per_page');
            $page = (int)(floor($id/$count)+1);
        }
        else
        {
            $page = array_get($this->item, 'page', 1);
            $count = array_get($this->item, 'count', 100);
        }

        return $prefix.'_'.$count.'_'.$page;
    }

    public function getData()
    {
        $page = array_get($this->item, 'page', 1);
        $count = array_get($this->item, 'count', 100);
        $ids = [];
        $start = ($page-1)*$count;
        $end = $start + $count;
        for($i = $start; $i < $end; $i++)
        {
            $ids[] = $i;
        }

        if(Request::has('debug'))
        {
            dd($ids);
        }

        return $this->requestBubblesByIds($ids);
    }

    protected function requestBubblesByIds($ids)
    {
        $params = [
            'ids' => $ids
        ];

        $query = "
            query Bubbles(\$ids: [String])
            {
                data: bubbles(ids: \$ids)
                {
                    ...bubbleFields
                }
            }
        ";
        $fragments = view('graphql.bubble')->render();
        $data = $this->requestGraphQL($query.$fragments, $params);
        return array_get($data, 'data', []);
    }
}
