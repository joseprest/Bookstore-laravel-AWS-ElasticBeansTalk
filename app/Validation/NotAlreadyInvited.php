<?php namespace Manivelle\Validation;

use Request;

use Manivelle\Models\Organisation;
use Manivelle\Models\OrganisationInvitation;
use Manivelle\User;

class NotAlreadyInvited
{
    
    public function validate($attribute, $value, $parameters, $validator)
    {
        $data = $validator->getData();
        if (isset($data['organisation_id'])) {
            $organisation = Organisation::find($data['organisation_id']);
        } else {
            $organisation = Request::route('organisation');
        }
        
        $user = User::where('email', 'LIKE', strtolower($value))->first();
        $query = OrganisationInvitation::where('email', 'LIKE', strtolower($value));
        if ($user) {
            $query->orWhere('user_id', 'LIKE', strtolower($user->id));
        }
        
        $invitation = $query->first();
        
        if ($invitation) {
            return false;
        }
        
        return true;
    }
}
