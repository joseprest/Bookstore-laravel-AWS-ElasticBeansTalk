<?php namespace Manivelle\GraphQL\Mutation;

use GraphQL;
use Request;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Mutation;

use Manivelle\Models\Screen;
use Manivelle\Models\Organisation;
use Ramsey\Uuid\Uuid;

class OrganisationCreateScreen extends Mutation
{
    protected $attributes = [
        'description' => 'Create a screen on an organisation'
    ];

    public function type()
    {
        return GraphQL::type('Screen');
    }

    public function args()
    {
        return [
            'organisation_id' => [
                'name' => 'organisation_id',
                'type' => Type::string(),
                'rules' => ['exists:organisations,id']
            ],
            'name' => [
                'name' => 'name',
                'type' => Type::string(),
                'rules' => ['required']
            ]
        ];
    }

    public function resolve($root, $args)
    {
        if (isset($args['organisation_id'])) {
            $organisation = Organisation::find($args['organisation_id']);
        } else {
            $organisation = Request::route('organisation');
        }

        if (!$organisation) {
            throw new \GraphQL\Error('Organisation not found');
        }

        $screen = new Screen();
        $screen->fill([
            'name' => $args['name'],
            'uuid' => Uuid::uuid1(),
        ]);
        $screen->save();

        return $organisation->linkScreen($screen);
    }
}
