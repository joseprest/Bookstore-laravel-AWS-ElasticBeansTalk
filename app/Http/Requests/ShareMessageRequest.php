<?php namespace Manivelle\Http\Requests;

class ShareMessageRequest extends Request
{
    public function authorize()
    {
        return true;
    }
    
    public function rules()
    {
        return [
            'email' => array('required', 'email'),
            'message' => array('required'),
            'bubble_id' => array('required', 'exists:bubbles,id')
        ];
    }
}
