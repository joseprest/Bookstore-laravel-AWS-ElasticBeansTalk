<?php namespace Manivelle\GraphQL\Query;

use GraphQL;
use Panneau;
use DB;
use Request;
use GraphQL\Type\Definition\Type;
use Illuminate\Pagination\LengthAwarePaginator;

class BubblesIdsPaginated extends Bubbles
{
    protected $attributes = [
        'description' => 'Paginated bubbles ids query'
    ];

    protected $defaultPage = 1;
    protected $defaultCount = 15;

    public function type()
    {
        return GraphQL::type('BubblesIdsPaginated');
    }

    public function args()
    {
        $args = parent::args();

        $args['total_count'] = [
            'name' => 'total_count',
            'type' => Type::int()
        ];

        return $args;
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
        $query = $resource->query($args);
        $totalCount = array_get($args, 'total_count', null);
        if ($totalCount === null) {
            $totalCount = $query->count();
        }
        $items = $query->skip($count * ($page-1))->take($count)->lists('bubbles.id');

        if (Request::has('DEBUG_QUERY')) {
            dd(DB::getQueryLog());
        }

        return new LengthAwarePaginator($items, $totalCount, $count, $page);
    }
}
