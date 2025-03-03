<?php

namespace App\Services\Customer;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use DateTime;

class CustomerPasswordService
{
    /**
     * Cron. Send mail for change password once a year
     */
    public function sendMailForUpdatePassword()
    {
        $time = new DateTime('now');
        $from = $time->modify('-1 year')->format('Y-m-d');
        $to = $time->modify('+8 day')->format('Y-m-d');
        $users = User::whereBetween('updated_at', [$from, $to])->get();

        if ($users) {
            foreach ($users as $user) {
                Mail::raw(
                        'You (login: ' . $user['email'] . ') have had the old password for over a year. We suggest you change it in ' . URL::to('/login'),
                        function ($message) use ($user) {
                            $message->to($user['email'], $user['username']);
                            $message->subject('Password change reminder');
                            $message->from(config('app.mailFromAddress'), 'Creo Server');
                        }
                );
            }
        }
    }
}