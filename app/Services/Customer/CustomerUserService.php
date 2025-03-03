<?php

namespace App\Services\Customer;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CustomerUserService
{

    public function save($customerId, $data)
    {
        $user = User::firstOrNew(['customerId' => $customerId]);
        $user->username = $data['username'];
        $user->customerId = $customerId;
        $user->tier = $data['saleId'];
        $user->email = $data['email'];
        $user->role = !empty($user->role) ? $user->role : 0;
        $user->time_update_password = date("Y-m-d H:i:s");
        $user->password = Hash::make($data['password']);
        $user->save();

        return $user->id;
    }

    public function saveUserWebshop($customerId, $data)
    {
        $user = User::firstOrNew(['customerId' => $customerId]);
        $user->username = $data['username'];
        $user->gender = 1;
        $user->customerId = $customerId;
        $user->tier = $data['saleId'];
        $user->email = $data['email'];
        $user->role = !empty($user->role) ? $user->role : 0;
        $user->time_update_password = date("Y-m-d H:i:s");
        $user->password = User::PASSWORD_EMPTY_WEBSHOP;
        $user->save();

        return $user->id;
    }
}