<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartPayRequest extends FormRequest
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
                'customer.username' => 'required|min:2|max:50',
                'customer.address' => 'required|min:2|max:50',
                'customer.postcode' => 'required|min:4|max:50',
                'customer.house' => 'required|min:1|max:50',

                'customer.region' => 'required|min:3',
                'customer.country' => 'required',

                'customer.email' => 'required|min:6|max:50|email',
                'customer.emailInvoice' => 'nullable|min:6|max:50',
                'customer.phone' => 'required|min:8|max:50|regex:/(^[0-9()+\- ]+$)+/',

                'customer.customerName' => 'nullable|max:50|min:3',
                'customer.btw' => 'nullable|max:50|min:3',
                'customer.kvk' => 'nullable|max:50|min:3',
        ];
    }

    public function messages()
    {
        return [
                'customer.username.min' => 'Contact Person field at least 2 characters',
                'customer.username.required' => 'Contact Person field is required',

                'customer.address.min' => 'Address field at least 2 characters',
                'customer.address.required' => 'Address field is required',

                'customer.postcode.min' => 'Postcode field at least 4 characters',
                'customer.postcode.required' => 'Postcode field is required',

                'customer.region.min' => 'Region field at least 4 characters',
                'customer.region.regex' => 'Region field incorrect input format',
                'customer.region.required' => 'Region field is required',

                'customer.email.min' => 'Email field at least 6 characters',
                'customer.email.regex' => 'Email field incorrect input format',
                'customer.email.required' => 'Email field is required',

                'customer.emailInvoice.min' => 'Email Invoice field at least 6 characters',
                'customer.emailInvoice.regex' => 'Email Invoice field incorrect input format',

                'customer.phone.min' => 'Phone field at least 8 characters',
                'customer.phone.regex' => 'Phone field incorrect input format',
                'customer.phone.required' => 'Phone field is required',

                'customer.country.required' => 'Country field is required',

                'customer.customerName.min' => 'Name company field at least 3 characters',

                'customer.btw.min' => 'btw field at least 3 characters',
                'customer.btw.max' => 'btw field at least 50 characters',

                'customer.kvk.min' => 'kvk field at least 3 characters',
                'customer.kvk.max' => 'kvk field at least 50 characters',

        ];
    }
}
