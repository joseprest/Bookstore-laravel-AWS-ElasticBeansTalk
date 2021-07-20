<?php namespace Manivelle\GraphQL\Mutation;

use GraphQL;
use Request;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Mutation;

use Manivelle\Models\Screen;
use Manivelle\Models\Organisation;

class OrganisationLinkScreen extends Mutation
{
    protected $attributes = [
        'description' => 'Link a screen to an organisation'
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
            'auth_code' => [
                'name' => 'auth_code',
                'type' => Type::string(),
                'rules' => ['required', 'exists:screens,auth_code']
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
        
        $screen = Screen::where('auth_code', $args['auth_code'])->first();
        
        if (!$screen) {
            throw new \GraphQL\Error('$auth_code: Screen not found');
        }
        
        return $organisation->linkScreen($screen);
    }
}
