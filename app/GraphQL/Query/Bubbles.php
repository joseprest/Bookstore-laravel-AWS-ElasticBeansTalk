<?php namespace Manivelle\GraphQL\Query;

use GraphQL;
use Manivelle;
use GraphQL\Type\Definition\Type;
use Request;
use Panneau;

class Bubbles extends ResourcesQuery
{
    protected $resource = 'bubbles';

    protected $attributes = [
        'description' => 'Bubbles query'
    ];

    public function type()
    {
        return Type::listOf(GraphQL::type('Bubble'));
    }

    public function args()
    {
        $args = parent::args();

        $args['playlist_id'] = [
            'name' => 'playlist_id',
            'type' => Type::string()
        ];
        $args['channel_id'] = [
            'name' => 'channel_id',
            'type' => Type::string()
        ];
        $args['screen_id'] = [
            'name' => 'screen_id',
            'type' => Type::string()
        ];
        $args['type'] = [
            'name' => 'type',
            'type' => Type::string()
        ];
        $args['id'] = [
            'name' => 'id',
            'type' => Type::string()
        ];
        $args['ids'] = [
            'name' => 'ids',
            'type' => Type::listOf(Type::string())
        ];

        $types = Manivelle::bubbleTypes();
        foreach ($types as $type) {
            $bubbleType = Manivelle::bubbleType($type);
            $filters = $bubbleType->getFilters();
            foreach ($filters as $filter) {
                $argName = 'filter_'.$type.'_'.$filter['name'];
                $args[$argName] = [
                    'name' => $argName,
                    'type' => Type::listOf(Type::string())
                ];
            }
        }

        return $args;
    }

    public function resolve($root, $args)
    {
        $args['order_by'] = 'bubbles.id';
        $args['with_relations'] = true;

        if (isset($args['channel_id'])) {
            $channel = Panneau::resource('channels')->find($args['channel_id']);
            if ($channel && $channel->bubblesAreByOrganisation()) {
                $organisation = Request::route('organisation');
                if ($organisation) {
                    $args['organisation_id'] = $organisation->id;
                }
            }
        }

        $items = parent::resolve($root, $args);
        return $items;
    }
}
