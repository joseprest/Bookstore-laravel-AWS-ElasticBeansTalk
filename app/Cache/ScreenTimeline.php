<?php

namespace Manivelle\Cache;

use Manivelle\Models\Screen;

use Manivelle\Cache\ScreenCache;

use Manivelle\Cache\Traits\GraphQLRequest;

class ScreenTimeline extends ScreenCache
{
    use GraphQLRequest;
    
    protected $key = 'screen_timeline';
    protected $cacheOnCloud = true;
    
    public function getData()
    {
        $params = [
            'screen_id' => $this->item->id
        ];
        
        $query = "
                    
            query Timeline(\$screen_id: String)
            {
                data: timeline(screen_id: \$screen_id)
                {
                    ...timelineFields
                }
            }

        ";
        $fragments = view('graphql.timeline')->render();
        
        $data = $this->requestGraphQL($query.$fragments, $params);
        return array_get($data, 'data', []);
    }
}
