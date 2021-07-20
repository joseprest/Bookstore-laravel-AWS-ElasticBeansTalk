<?php namespace Manivelle\GraphQL\Mutation;

use GraphQL;
use Request;
use Auth;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Mutation;

use Manivelle\Models\Organisation;
use Manivelle\Models\OrganisationInvitation;
use Manivelle\User;
use Manivelle\Models\Role;

class OrganisationRemoveInvitation extends Mutation
{
    protected $attributes = [
        'description' => 'Remove an invitation in an organisation'
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
            'invitation_id' => [
                'name' => 'invitation_id',
                'type' => Type::string(),
                'rules' => ['required', 'exists:organisations_invitations,id']
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
        
        $invitation = OrganisationInvitation::find($args['invitation_id']);
        
        return $organisation->removeInvitation($invitation);
    }
}
