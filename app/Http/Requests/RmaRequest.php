<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RmaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
                'serialNumbers' => 'nullable|max:255',
                'orderInput' => 'nullable|max:255',
        ];
    }


    public function messages()
    {
        return [
                'serialNumbers.max' => 'The maximum length of the serial number is 255',
                'orderInput.max' => 'The maximum length of the order number is 255',
        ];
    }
}
