<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChatFileInfoController extends Controller
{

    public function store(Request $request)
    {
        $data = $request->get('file');
        return response(Storage::disk('sftpFiles')->get($data['disk_name']), 200)
            ->header('Content-type', 'application/pdf');
    }
}
