<?php

namespace Manivelle\Cache;

use Manivelle\Models\Screen;
use Log;
use Cache as CacheFacade;

use Manivelle\Cache\ScreenCache;

use Manivelle\Cache\Traits\GraphQLRequest;

class ScreenBubblesIds extends ScreenCache
{
    use GraphQLRequest;

    protected $key = 'screen_data_bubbles_ids';
    protected $cacheOnCloud = true;

    protected $idsPerPage = 1000;

    public function getData()
    {
        $page = 1;
        $totalPage = -1;
        $totalCount = null;
        $ids = [];
        do {
            //$response = $this->requestBubbleIdsPage($page, $totalCount);
            $response = $this->requestBubbleIds();

            if (\Request::has('DEBUG')) {
                dump($page);
                dump(array_get($response, 'pagination', []));
            }

            $totalPage = array_get($response, 'pagination.last_page', 1);
            $totalCount = array_get($response, 'pagination.total', null);
            $pageIds = array_get($response, 'ids', []);
            $ids[] = implode(',', $pageIds);
            unset($response);

            $memory = round(memory_get_usage(true)/1024/1024, 2);
            Log::info('[Cache ScreenBubblesIds] Page '.$page.' of '.$totalPage.' requested for screen #'.$this->item->id.'. '.sizeof($pageIds).' ids. Memory: '.$memory.' MB');

            if (\Request::has('DEBUG')) {
                dump($totalCount);
                dump($totalPage);
                dump($ids);
                if ($page > 2) {
                    die();
                }
            }

            $page++;
        } while ($page <= $totalPage);

        if (\Request::has('DEBUG')) {
            dump($totalPage);
            dump(sizeof($ids));
        }

        ob_start();
        echo '[';
        $i = 0;
        foreach ($ids as $id) {
            if ($i > 0) {
                echo ',';
            }
            echo $id;
            unset($id);
            $i++;
        }
        echo ']';
        $contents = ob_get_clean();

        if (\Request::has('DEBUG')) {
            die();
        }

        return $contents;
    }

    protected function requestBubbleIds()
    {
        $params = [
            'screen_id' => $this->item->id
        ];

        $query = "
            query Bubbles(\$screen_id: String)
            {
                data: bubblesIds(screen_id: \$screen_id)
                {
                    ids
                }
            }
        ";

        $data = $this->requestGraphQL($query, $params);
        return array_get($data, 'data', []);
    }

    protected function requestBubbleIdsPage($page, $totalCount = null)
    {
        $params = [
            'screen_id' => $this->item->id,
            'page' => $page,
            'count' => $this->idsPerPage
        ];

        if ($totalCount) {
            $params['total_count'] = $totalCount;
        }

        $query = "
            query Bubbles(\$screen_id: String, \$page: Int, \$count: Int, \$total_count: Int)
            {
                data: bubblesIdsPaginated(screen_id: \$screen_id, page: \$page, count: \$count, total_count: \$total_count)
                {
                    pagination {
                        last_page
                        total
                    }
                    ids
                }
            }
        ";

        $data = $this->requestGraphQL($query, $params);
        return array_get($data, 'data', []);
    }
}
