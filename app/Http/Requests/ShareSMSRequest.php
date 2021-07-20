<?php namespace Manivelle\Http\Requests;

class ShareSMSRequest extends Request
{
    public function authorize()
    {
        return true;
    }
    
    public function rules()
    {
        return [
            'phone' => array('required'),
            'bubble_id' => array('required', 'exists:bubbles,id')
        ];
    }
}
