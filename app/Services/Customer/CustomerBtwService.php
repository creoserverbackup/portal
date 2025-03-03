<?php

namespace App\Services\Customer;

use App\Models\CartOrderPayment;
use App\Models\Country;
use App\Models\Customers;
use App\Models\CustomersAddress;
use Illuminate\Support\Facades\DB;
use PH7\Eu\Vat\Provider\Europa;
use PH7\Eu\Vat\Validator;

class CustomerBtwService
{

    const YOUR_API_KEY = 'n7m3vqrp9m874goicqm78vtavg3n1dt8regndijrnfo4bvjk30n9k';

    public function checkBTW($customerId)
    {
        $customerBeforeUpdate = Customers::where('customerId', $customerId)->first();
        $customerAddress = CustomersAddress::where('customerId', $customerId)->first();

        try {
            $vat = $this->getVat($customerAddress->country, $customerBeforeUpdate->btw);
            $needNDS = $vat == CartOrderPayment::VAT ? Customers::STATUS_PAY_NDS['need'] : Customers::STATUS_PAY_NDS['noNeed'];
        } catch (\Exception $e) {
            $needNDS = Customers::STATUS_PAY_NDS['need'];
        }

        if (!empty($customerBeforeUpdate)) {
            $customerBeforeUpdate->needNDS = $needNDS;
            $customerBeforeUpdate->saveQuietly();
        }
    }

    public function checkInput()
    {
        $data = request()->get('customer');

        $result = [
          'valid' => false,
          'error' => '',
        ];

        if (empty($data['country']) || empty($data['btw'])) {
            return $result;
        }

//        if (!is_numeric($data['btw'])) {
//            $result['error'] = "Please do <span class='td-underline'>NOT</span> use your country code for this!";
//            return $result;
//        }


        try {
            $data['country'] = 'Belgium' == $data['country'] ? 'Belgie' : $data['country'];
            $data['country'] = 'Netherlands' == $data['country'] ? 'Nederland' : $data['country'];

            $country = DB::table('countries')
                    ->selectRaw('codeCountry')
                    ->where('countryName', $data['country'])
                    ->first();

            if ($this->requestCheck($data['btw'], $country->codeCountry)) {
                $result['valid'] = true;
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::info(print_r("Error CustomerBtwService", true));
            \Illuminate\Support\Facades\Log::info(print_r($e->getMessage(), true));
            $result['error'] = 'Check is not available';
        }
        return $result;
    }

    public function getVat($country, $btw)
    {
        $vat = CartOrderPayment::VAT;

        $country = 'Belgium' == $country ? 'Belgie' : $country;
        $country = 'Netherlands' == $country ? 'Nederland' : $country;
        $countryCode = Country::where('countryName', $country)->first();

        if (empty($countryCode) || $countryCode->codeCountry == 'NL' || empty($btw)) {
            return $vat;
        }

        try {
            if ($this->requestCheck($btw, $countryCode->codeCountry)) {
                return 0;
            } else {
                return $vat;
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::info(print_r("Error getVat", true));
            \Illuminate\Support\Facades\Log::info(print_r($e->getMessage(), true));
            \Illuminate\Support\Facades\Log::info(print_r($country, true));
            \Illuminate\Support\Facades\Log::info(print_r($btw, true));
            return $this->getVatAnother($countryCode->codeCountry, $btw);
        }
    }

    public function getVatAnother($codeCountry, $btw)
    {
        $vat = CartOrderPayment::VAT;
        try {
            $client = new \SoapClient("https://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl");
            $params = [
                    'countryCode' => $codeCountry,
                    'vatNumber' => $btw,
            ];
            $result = $client->checkVatApprox($params);

            if (isset($result->valid) && !empty($result->valid)) {
                return 0;
            } else {
                return $vat;
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::info(print_r("Error getVatAnother", true));
            \Illuminate\Support\Facades\Log::info(print_r($e->getMessage(), true));
            \Illuminate\Support\Facades\Log::info(print_r($codeCountry, true));
            \Illuminate\Support\Facades\Log::info(print_r($btw, true));
            return $this->getVatAnotherCustomer($codeCountry, $btw);
        }
    }

    public function getVatAnotherCustomer($codeCountry, $btw)
    {
        $customer = Customers::where(['btw' => $codeCountry. $btw])
                ->where('needNDS', Customers::STATUS_PAY_NDS['noNeed'])
                ->first();

        \Illuminate\Support\Facades\Log::info(print_r("SEND getVatAnotherCustomer", true));
        \Illuminate\Support\Facades\Log::info(print_r($codeCountry, true));
        \Illuminate\Support\Facades\Log::info(print_r($btw, true));

        if (!empty($customer)) {
            return 0;
        } else {
            return CartOrderPayment::VAT;
        }
    }

    /**
     * @throws \Exception
     */
    public function requestCheck($btw, $codeCountry): bool
    {
        try {
        $oVatValidator = new Validator(new Europa, $btw, $codeCountry);
        return $oVatValidator->check();
        } catch (\Exception $e) {
            return $this->checkAnotherMethod($btw, $codeCountry);
        }
    }

    public function checkAnotherMethod($btw, $codeCountry): bool|string
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
                CURLOPT_URL => "https://anyapi.io/api/v1/vat/validate?vat_number=" . $codeCountry . $btw ."&apiKey=" . self::YOUR_API_KEY,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
        ]);

        $response = json_decode(curl_exec($curl));
        $err = curl_error($curl);

        curl_close($curl);

        $data = request()->all();
        \Illuminate\Support\Facades\Log::info(print_r("checkAnotherMethod = "  .  date("Y-m-d H:i:s"),  true));
        \Illuminate\Support\Facades\Log::info(print_r($data, true));
        \Illuminate\Support\Facades\Log::info(print_r($response, true));

        if (!empty($response) && isset($response->valid) && !empty($response->valid)) {
            return true;
        } else {
            return false;
        }
    }
}