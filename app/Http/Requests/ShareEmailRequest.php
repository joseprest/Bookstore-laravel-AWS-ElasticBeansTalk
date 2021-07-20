<?php namespace Manivelle\Http\Requests;

class ShareEmailRequest extends Request
{
    public function authorize()
    {
        return true;
    }
    
    public function rules()
    {
        return [
            'email' => array('required', 'email'),
            'bubble_id' => array('required', 'exists:bubbles,id')
        ];
    }
}
