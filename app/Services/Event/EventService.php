<?php

namespace App\Services\Event;

use App\Events\UpdateOrderUser;
use App\Models\StatisticVisits;
use Illuminate\Support\Facades\Http;

class EventService
{
    public function createNewRma($orderIdRma, $rmaId)
    {
        $url = config('app.pathWorkFlow') . "/create/order/rma";
        $url .= '?orderId=' . $orderIdRma . '&rmaId=' . $rmaId;
        return Http::get($url);
    }

    public function createOfferteCart($orderId): \Illuminate\Http\Client\Response
    {
        $mailNeed = request()->get("frame") ? '' : 'true';
        $url = config('app.pathWorkFlow') . "/order/offerte/cart?mail=" . $mailNeed;
        $url .= '&orderId=' . $orderId;
        return Http::get($url);
    }


    public function sendCartFrame($uid, $type, $orderId = '')
    {
        $url = config('app.pathWorkFlow') . "/mail/cart/frame?uid=" . $uid . "&type=" . $type;
        $url .= '&orderId=' . $orderId;
        return Http::get($url);
    }

    public function createNewChat($chatId)
    {
        $url = config('app.pathWorkFlow') . "/create/chat";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url . '?chatId=' . $chatId);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($curl);
        curl_close($curl);

        return $output;
    }

    public function createNewOffer($orderId, $uid, $mail = true): bool|string
    {
        $url = config('app.pathWorkFlow') . "/order/offer/create";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url . '?orderId=' . $orderId . "&mail=" . $mail);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($curl);
        curl_close($curl);

        event(new UpdateOrderUser($uid));

        return $output;
    }

    public function destroyOffer($orderId): bool|string
    {
        $url = config('app.pathWorkFlow') . "/order/offer/destroy";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url . '?orderId=' . $orderId);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }

    public function createNewCustomer($customerName, $key, $site, $password)
    {
        if (!empty($site)) {
            $placeCreate = StatisticVisits::TYPE_SITE['webshop'];
        } else {
            $placeCreate = StatisticVisits::TYPE_SITE['portal'];
        }

        $password = 'xxxxx' . mb_substr($password, -4);

        $url = config('app.pathWorkFlow') . "/create/customer";
        $curl = curl_init();
        curl_setopt(
                $curl,
                CURLOPT_URL,
                $url . '?customerName=' . $customerName . '&placeCreate=' . $placeCreate . '&key=' . $key . '&password=' . $password
        );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($curl);
        curl_close($curl);

        return $output;
    }

    public function sendMailProforma($orderId)
    {
        $url = config('app.pathWorkFlow') . "/mail/order/proforma/" . $orderId;
        return Http::get($url);
    }

    public function proformaAccept($orderId)
    {
        $url = config('app.pathWorkFlow') . "/proforma/accept" . '?orderId=' . $orderId;
        return Http::get($url);
    }


    public function createNewFactuurOrder($orderId)
    {
        $url = config('app.pathWorkFlow') . "/create/order/factuur";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url . '?orderId=' . $orderId);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($curl);
        curl_close($curl);

        return $output;
    }

    public function createNewRequest($requestId)
    {
        $url = config('app.pathWorkFlow') . "/create/request";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url . '?requestId=' . $requestId);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }

    public function createNewTicket($ticketId)
    {
        $url = config('app.pathWorkFlow') . "/create/ticket";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url . '?ticketId=' . $ticketId);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($curl);
        curl_close($curl);

        return $output;
    }

}
