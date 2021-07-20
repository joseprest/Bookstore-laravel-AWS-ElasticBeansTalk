<?php namespace Manivelle\Http\Requests;

class TeamInviteRequest extends Request
{
    
    public function authorize()
    {
        return true;
    }
    
    public function rules()
    {
        return [
            'email' => array('required', 'email'),
            'role' => array('required')
        ];
    }
}
