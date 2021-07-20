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

class ChannelFilterValues extends Query
{
    protected $attributes = [
        'description' => 'Channels query'
    ];

    public function type()
    {
        return Type::listOf(GraphQL::type('ChannelFilterValue'));
    }

    public function args()
    {
        return [
            'id' => ['name' => 'id', 'type' => Type::nonNull(Type::string())],
            'filter' => ['name' => 'filter', 'type' => Type::nonNull(Type::string())]
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

        $values = $filter ? $channeltype->getBubbleFilterValues($filter['name']):[];
        return $values;
        //return $filter ? array_get($filter, 'values', []):[];
    }
}
