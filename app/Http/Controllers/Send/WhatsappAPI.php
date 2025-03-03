<?php

namespace App\Http\Controllers\Send;

use App\Http\Controllers\Controller;

class WhatsappAPI extends Controller
{
    private $id = 792;
    private $key = "169fd8dad469f56ee4eac8a853d822898ab47be0";

    public function send($send_to, $message_body)
    {
        $data = array('to' => $send_to, 'msg' => $message_body);

        $url = "https://onyxberry.com/services/wapi/Client/sendMessage";
        $url = $url . '/' . $this->id . '/' . $this->key;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        return curl_exec($ch);
    }
}
?>
