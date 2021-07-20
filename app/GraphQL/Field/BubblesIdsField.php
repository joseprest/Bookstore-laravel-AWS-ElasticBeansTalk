<?php namespace Manivelle\GraphQL\Field;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Field;

use Panneau;
use Manivelle\Models\ChannelPivot;
use Request;

class BubblesIdsField extends Field
{
    public function type()
    {
        return Type::listOf(Type::string());
    }

    public function resolve($root)
    {
        $params = [
            'channel_id' => $root->channel_id,
        ];
        if ($root instanceof ChannelPivot && isset($root->settings->filters)) {
            foreach ($root->settings->filters as $filter) {
                if (isset($filter->value) && isset($filter->name)) {
                    $params['filter_' . $filter->name] = $filter->value;
                }
            }
        }

        if (isset($params['channel_id'])) {
            $channel = Panneau::resource('channels')->find($params['channel_id']);
            if ($channel && $channel->bubblesAreByOrganisation()) {
                $organisation = Request::route('organisation');
                if ($organisation) {
                    $params['organisation_id'] = $organisation->id;
                }
            }
        }

        $query = Panneau::resource('bubbles')->query($params);
        return $query->lists('bubbles.id');
    }
}
