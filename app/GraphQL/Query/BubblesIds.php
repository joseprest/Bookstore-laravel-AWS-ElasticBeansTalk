<?php namespace Manivelle\GraphQL\Query;

use GraphQL;
use DB;
use Request;
use Panneau;
use GraphQL\Type\Definition\Type;
use Manivelle\Models\Screen;

class BubblesIds extends Bubbles
{
    protected $attributes = [
        'description' => 'Paginated bubbles ids query'
    ];

    protected $defaultPage = 1;
    protected $defaultCount = 15;

    public function type()
    {
        return GraphQL::type('BubblesIds');
    }

    public function resolve($root, $args)
    {
        if (Request::has('DEBUG_QUERY')) {
            DB::enableQueryLog();
        }

        if (isset($args['screen_id']) && sizeof($args) === 1) {
            $screen = Screen::findOrFail($args['screen_id']);
            return $screen->getBubbleIds();
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
        $query = $resource->query($args);
        $items = $query->lists('bubbles.id');

        if (Request::has('DEBUG_QUERY')) {
            dd(DB::getQueryLog());
        }

        return $items;
    }
}
