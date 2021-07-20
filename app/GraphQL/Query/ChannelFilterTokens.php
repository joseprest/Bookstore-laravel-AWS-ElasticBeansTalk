<?php namespace Manivelle\GraphQL\Query;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Panneau;
use Manivelle\Models\Channel;
use Request;
use Cache;
use Manivelle;

use Folklore\GraphQL\Support\Query;

use Illuminate\Support\Str;

class ChannelFilterTokens extends Query
{
    protected $attributes = [
        'description' => 'Channels query'
    ];
    
    public function type()
    {
        return Type::listOf(GraphQL::type('ChannelFilterToken'));
    }
    
    public function args()
    {
        return [
            'id' => ['name' => 'id', 'type' => Type::nonNull(Type::string())],
            'filter' => ['name' => 'filter', 'type' => Type::nonNull(Type::string())],
            'search' => ['name' => 'search', 'type' => Type::string()],
        ];
    }
    
    public function resolve($root, $args)
    {
        $id = array_get($args, 'id');
        $filterName = array_get($args, 'filter');
        
        $channel = Channel::findOrFail($id);
        $channeltype = $channel->getChannelType();
        $bubblesFilters = $channeltype->getBubblesFilters();
        
        $filter = array_first($bubblesFilters, function ($key, $value) use ($filterName) {
            return array_get($value, 'name') === $filterName;
        });
        
        $tokens = $filter ? $channeltype->getBubbleFilterTokens($filter['name']):[];
        //$tokens = $filter ? array_get($filter, 'tokens', []):[];
        
        $foundValues = array_where($tokens, function ($key, $value) use ($args) {
            $found = true;
            if (isset($args['search'])) {
                $label = is_string($value) ? $value:$value['label'];
                $label = Str::slug($label);
                $search = Str::slug($args['search']);
                if (strpos($label, $search) === false) {
                    $found = false;
                }
            }
            return $found;
        });
        
        return $foundValues;
    }
}
