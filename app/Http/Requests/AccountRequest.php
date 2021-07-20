<?php namespace Manivelle\Http\Requests;

class AccountRequest extends Request
{
    
    public function authorize()
    {
        return true;
    }
    
    public function rules()
    {
        return [
            'name' => array('required'),
            'email' => array('required', 'email'),
            'password' => array('confirmed')
        ];
    }
}
