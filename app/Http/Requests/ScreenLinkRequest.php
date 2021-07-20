<?php namespace Manivelle\Http\Requests;

class ScreenLinkRequest extends Request
{
    
    public function authorize()
    {
        return true;
    }
    
    public function rules()
    {
        return [
            'auth_code' => array('required', 'exists:screens,auth_code')
        ];
    }
}
