<?php namespace Manivelle\GraphQL\Query;

use GraphQL;
use Panneau;
use DB;
use Request;
use GraphQL\Type\Definition\Type;

class BubblesPaginated extends Bubbles
{
    protected $attributes = [
        'description' => 'Paginated bubbles query'
    ];

    protected $defaultPage = 1;
    protected $defaultCount = 15;

    public function type()
    {
        return GraphQL::type('BubblesPaginated');
    }

    public function resolve($root, $args)
    {
        if (Request::has('DEBUG_QUERY')) {
            DB::enableQueryLog();
        }

        if (isset($args['channel_id'])) {
            $channel = Panneau::resource('channels')->find($args['channel_id']);
            if ($channel && $channel->bubblesAreByOrganisation()) {
                $organisation = Request::route('organisation');
                if ($organisation) {
                    $args['organisation_id'] = $organisation->id;
                }
            }
        }

        $resource = Panneau::resource($this->resource);
        $page = array_get($args, 'page', $this->defaultPage);
        $count = array_get($args, 'count', $this->defaultCount);
        $args['order_by'] = 'bubbles.id';
        $args['withRelations'] = true;
        $items = $resource->get($args, $page, $count);

        if (Request::has('DEBUG_QUERY')) {
            dd(DB::getQueryLog());
        }

        return $items;
    }
}
