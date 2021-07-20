<?php namespace Manivelle\Http\Requests;

class InvitationRegisterRequest extends Request
{
    
    public function authorize()
    {
        return true;
    }
    
    public function rules()
    {
        return [
            'name' => array('required'),
            'email' => array('required', 'email', 'unique:users'),
            'password' => array('required', 'confirmed')
        ];
    }
}
