<?php namespace Manivelle\GraphQL\Mutation;

use GraphQL;
use Request;
use Auth;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Mutation;

use Manivelle\Models\Organisation;
use Manivelle\User;
use Manivelle\Models\Role;

class OrganisationInviteUser extends Mutation
{
    protected $attributes = [
        'description' => 'Invite a user to an organisation'
    ];
    
    public function type()
    {
        return GraphQL::type('OrganisationInvitation');
    }
    
    public function args()
    {
        return [
            'organisation_id' => [
                'name' => 'organisation_id',
                'type' => Type::string(),
                'rules' => ['exists:organisations,id']
            ],
            'email' => [
                'name' => 'email',
                'type' => Type::string(),
                'rules' => ['required', 'email', 'user_not_in_organisation', 'not_already_invited']
            ],
            'role_id' => [
                'name' => 'role_id',
                'type' => Type::string(),
                'rules' => ['required', 'exists:roles,id']
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
        
        $role = Role::find($args['role_id']);
        
        if (!$role) {
            throw new \Exception('Role not found');
        }
        
        return $organisation->inviteUser($args['email'], $role);
    }
}
