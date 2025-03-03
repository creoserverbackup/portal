<?php


namespace App\Http\Controllers\Auth;

use App\Models\Customers;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;

class SocialController extends Controller
{
    public function redirectToGoogle()
    {

        $data = request()->all();
        \Illuminate\Support\Facades\Log::info(print_r("redirectToGoogle = "  .  date("Y-m-d H:i:s"),  true));
        \Illuminate\Support\Facades\Log::info(print_r($data, true));


        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $data = request()->all();
        \Illuminate\Support\Facades\Log::info(print_r("handleGoogleCallback = "  .  date("Y-m-d H:i:s"),  true));
        \Illuminate\Support\Facades\Log::info(print_r($data, true));
        \Illuminate\Support\Facades\Log::info(print_r($googleUser, true));


        $customer = DB::table('users', 'u')
                ->join('customers as c', 'c.customerId', '=', 'u.customerId')
                ->where('u.email', $googleUser->getEmail())
                ->where('c.clientBlocked', Customers::CLIENT_BLOCKED['no'])
                ->first();

        if (!empty($customer)) {
            $user = User::where('customerId', $customer->customerId)->first();
            $user->google_id = $googleUser->getId();
            $user->save();

            Auth::login($user);
            return redirect(config('app.webshop_url') . '/accounts/#/');
        } else {
            return redirect(config('app.webshop_url') . '/register/step-one');
        }
    }
}
