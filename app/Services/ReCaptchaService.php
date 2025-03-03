<?php

namespace App\Services;

class ReCaptchaService
{

    public function checkReCaptcha($captchaV3 = false): bool
    {

        if (!empty(request()->header('webshop')) || $captchaV3) {
            $secret = config('services.recaptcha.secret');
        } else {
            $secret = config('services.recaptchaV2.secret');
        }

        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = [
            'secret' => $secret,
            'response' => request()->get('recaptcha'),
            'remoteip' => $_SERVER['REMOTE_ADDR']
        ];
        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            ]
        ];
        $context = stream_context_create($options);
        $result = json_decode(file_get_contents($url, false, $context));

        return isset($result->success) && !empty($result->success);
    }
}
