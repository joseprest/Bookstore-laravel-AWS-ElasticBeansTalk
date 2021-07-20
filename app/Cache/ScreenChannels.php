<?php

namespace Manivelle\Cache;

use Manivelle\Models\Screen;
use Log;

use Manivelle\Cache\ScreenCache;

use Manivelle\Cache\Traits\GraphQLRequest;

class ScreenChannels extends ScreenCache
{
    use GraphQLRequest;
    
    protected $key = 'screen_channels';
    protected $cacheOnCloud = true;
    
    public function getData()
    {
        $ids = $this->item->channels()->groupBy('channel_id')->lists('channel_id');
        $channels = [];
        
        foreach ($ids as $id) {
            $channel = $this->getChannel($id);
            if ($channel) {
                $json = json_encode($channel);
                $channels[] = $json;
                unset($channel);
                
                $memory = round(memory_get_usage(true)/1024/1024, 2);
                Log::info('[Cache ScreenChannels] Channel #'.$id.' requested for screen #'.$this->item->id.'. Memory: '.$memory.' MB');
            }
        }
        
        ob_start();
        echo '[';
        $i = 0;
        foreach ($channels as $channel) {
            if ($i > 0) {
                echo ',';
            }
            echo $channel;
            unset($channel);
            $i++;
        }
        echo ']';
        $contents = ob_get_clean();
        unset($channels);
        
        return $contents;
    }
    
    protected function getChannel($id)
    {
        $params = [
            'screen_id' => (string)$this->item->id,
            'id' => (string)$id
        ];
        
        $query = "
                    
            query Channels(\$screen_id: String, \$id: String)
            {
                data: channels(screen_id: \$screen_id, id: \$id)
                {
                    ...channelFields
                }
            }

        ";
        $fragments = view('graphql.channel')->render();
        
        $data = $this->requestGraphQL($query.$fragments, $params);
        return array_get($data, 'data.0', null);
    }
}
