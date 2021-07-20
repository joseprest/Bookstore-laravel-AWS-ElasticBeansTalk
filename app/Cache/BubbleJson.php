<?php

namespace Manivelle\Cache;

use Manivelle\Models\Screen;
use Log;
use Cache as CacheFacade;

use Manivelle\Cache\Traits\GraphQLRequest;

class BubbleJson extends Cache
{
    use GraphQLRequest;
    
    protected $key = 'bubble_json';
    protected $cacheOnCloud = true;
    
    public function getData()
    {
        return $this->requestBubbleById($this->item->id);
    }
    
    protected function requestBubbleById($id)
    {
        $params = [
            'id' => $id
        ];
        
        $query = "    
            query Bubbles(\$id: String)
            {
                data: bubbles(id: \$id)
                {
                    ...bubbleFields
                }
            }
        ";
        $fragments = view('graphql.bubble')->render();
        
        $data = $this->requestGraphQL($query.$fragments, $params);
        return array_get($data, 'data.0', []);
    }
}
