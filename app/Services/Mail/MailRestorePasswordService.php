<?php

namespace App\Services\Mail;

use Illuminate\Support\Facades\Http;

class MailRestorePasswordService
{

    public function send($email)
    {
        $url = config('app.pathWorkFlow') . "/api/public/mail/restore_password/" . $email;
        $response = Http::get($url);
    }
}
