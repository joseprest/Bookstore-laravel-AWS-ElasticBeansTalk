<?php namespace Manivelle\GraphQL\Mutation;

use GraphQL;
use Request;
use Auth;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Mutation;

use Manivelle\Models\Organisation;
use Manivelle\User;
use Manivelle\Models\Role;

class OrganisationRemoveUser extends Mutation
{
    protected $attributes = [
        'description' => 'Remove a user in an organisation'
    ];
    
    public function type()
    {
        return GraphQL::type('User');
    }
    
    public function args()
    {
        return [
            'organisation_id' => [
                'name' => 'organisation_id',
                'type' => Type::string(),
                'rules' => ['exists:organisations,id']
            ],
            'user_id' => [
                'name' => 'user_id',
                'type' => Type::string(),
                'rules' => ['required', 'exists:users,id']
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
            throw new \Exception('Organisation not found');
        }
        
        if (!Auth::user()->can('teamManage', $organisation)) {
            return abort(403);
        }
        
        $user = User::find($args['user_id']);
        
        return $organisation->removeUser($user);
    }
}
