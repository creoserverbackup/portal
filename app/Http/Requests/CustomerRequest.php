<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
            'password' => 'required|min:9|max:30',
            'email' => 'required|min:6|max:50|email',
            'phone' => 'required|min:8|max:50|regex:/(^[0-9()+\- ]+$)+/',

            'region' => 'required|min:3',
            'country' => 'required',
            'passwordRepeat' => function ($attribute, $value, $fail) {
                if (!$this->checkPassword()) {
                    $fail('Passwords don\'t match');
                }
            },
//            'repeat email' => function ($attribute, $value, $fail) {
//                if ($this->checkEmail()) {
//                    $fail('This email is busy');
//                }
//            }
        ];
    }

    public function messages()
    {
        return [
            'customerName.min' => 'Name company field at least 3 characters',
//            'customerName.required' => 'Name company field is required',

            'address.min' => 'Address field at least 2 characters',
//            'address.regex' => 'Address field incorrect input format',
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

            'password.min' => 'Password field at least 9 characters',
            'password.required' => 'Password field is required',

            'phone.min' => 'Phone field at least 8 characters',
            'phone.regex' => 'Phone field incorrect input format',
            'phone.required' => 'Phone field is required',

            'country.required' => 'Country field is required',
        ];
    }

    public function checkPassword()
    {
        return $this->get('password') === $this->get('passwordRepeat');
    }

    public function checkEmail()
    {
        $data = request()->all();
        $query = User::where('email', $data['email']);

        if (!empty($data['customerId'])) {
            $query->where('customerId', '!=', $data['customerId']);
        }

        $customer = $query->first();
        return !empty($customer);
    }
}
