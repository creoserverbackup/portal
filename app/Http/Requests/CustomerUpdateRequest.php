<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerUpdateRequest extends FormRequest
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
                'customerName' => 'nullable|max:50|min:3',
                'address' => 'required|min:2|max:50',
                'house' => 'required|min:1|max:50',
                'postcode' => 'required|min:4|max:50',
                'mailbox' => 'nullable|min:5|max:50|regex:/(^[A-Za-z0-9 ]+$)+/',
                'username' => 'required|min:2|max:50',
                'email' => 'required|min:6|max:50|email',
                'emailInvoice' => 'nullable|min:6|max:50',
                'phone' => 'required|min:8|max:50|regex:/(^[0-9()+\- ]+$)+/',
                'region' => 'required|min:3',
                'country' => 'required',

                'deliveryCustomerName' => 'nullable|max:50|min:3',
                'deliveryUsername' => 'required|min:2|max:50',
                'deliveryAddress' => 'required|min:2|max:50',
                'deliveryPostcode' => 'required|min:4|max:50',
                'deliveryHouse' => 'required|min:1|max:50',
                'deliveryRegion' => 'required|min:3',
                'deliveryEmail' => 'required|min:6|max:50|email',
                'deliveryPhone' => 'required|min:8|max:50|regex:/(^[0-9()+\- ]+$)+/',
                'deliveryCountry' => 'required',
        ];
    }

    public function messages()
    {
        return [
                'customerName.min' => 'Name company field at least 3 characters',

                'address.min' => 'Address field at least 2 characters',
                'address.required' => 'Address field is required',

                'postcode.min' => 'Postcode field at least 4 characters',
                'postcode.required' => 'Postcode field is required',

                'region.min' => 'Region field at least 4 characters',
                'region.regex' => 'Region field incorrect input format',
                'region.required' => 'Region field is required',

                'mailbox.min' => 'Mailbox field at least 5 characters',
                'mailbox.regex' => 'Mailbox field incorrect input format',

                'username.min' => 'Contact Person field at least 2 characters',
                'username.required' => 'Contact Person field is required',

                'email.min' => 'Email field at least 6 characters',
                'email.regex' => 'Email field incorrect input format',
                'email.required' => 'Email field is required',

                'emailInvoice.min' => 'Email Invoice field at least 6 characters',
                'emailInvoice.regex' => 'Email Invoice field incorrect input format',

                'phone.min' => 'Phone field at least 8 characters',
                'phone.regex' => 'Phone field incorrect input format',
                'phone.required' => 'Phone field is required',

                'country.required' => 'Country field is required',


                'deliveryUsername.min' => 'Contact Person field at least 2 characters',
                'deliveryUsername.required' => 'Contact Person field is required',
                'deliveryCustomerName.min' => 'Name company field at least 3 characters',

                'deliveryAddress.min' => 'Address field at least 2 characters',
                'deliveryAddress.required' => 'Address field is required',

                'deliveryPostcode.min' => 'Postcode field at least 4 characters',
                'deliveryPostcode.required' => 'Postcode field is required',

                'deliveryRegion.min' => 'Region field at least 4 characters',
                'deliveryRegion.regex' => 'Region field incorrect input format',
                'deliveryRegion.required' => 'Region field is required',

                'deliveryEmail.min' => 'Email field at least 6 characters',
                'deliveryEmail.regex' => 'Email field incorrect input format',
                'deliveryEmail.required' => 'Email field is required',

                'deliveryPhone.min' => 'Phone field at least 8 characters',
                'deliveryPhone.regex' => 'Phone field incorrect input format',
                'deliveryPhone.required' => 'Phone field is required',

                'deliveryCountry.required' => 'Country field is required',
        ];
    }
}
