<?php namespace Manivelle\Validation;

use Request;

use Manivelle\Models\Organisation;
use Manivelle\User;

class UserNotInOrganisation
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
        if ($user) {
            $currentOrganisation = $user->organisations->first(function ($key, $item) use ($organisation) {
                return (int)$item->organisation_id === (int)$organisation->id;
            });
            if ($currentOrganisation) {
                return false;
            }
        }
        
        return true;
    }
}
