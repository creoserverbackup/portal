<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class AuthLoginConnectController extends Controller
{

    public function index()
    {
        $data['connect'] = checkdnsrr('php.net');
        return response()->json($data);
    }
}