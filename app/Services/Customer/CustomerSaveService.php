<?php

namespace App\Services\Customer;

use App\Events\UpdateCustomer;
use App\Models\CartOrderInfo;
use App\Models\CartOrderPayment;
use App\Models\Customers;
use App\Models\RegistrationByMail;
use App\Models\StatisticVisits;
use App\Services\Event\EventService;
use App\Services\ReCaptchaService;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CustomerSaveService
{

    public EventService $eventService;
    public ReCaptchaService $reCaptchaService;
    public CustomerUidService $customerUidService;
    public CustomerAddressService $customerAddressService;
    public CustomerLogoService $customerLogoService;
    public CustomerValidationService $customerValidationService;
    public CustomerDayWorkService $customerDayWorkService;
    public CustomerUserService $customerUserService;
    public CustomerBtwService $customerBtwService;
    public CustomerDeliveryService $customerDeliveryService;

    public function __construct()
    {
        $this->eventService = new EventService();
        $this->reCaptchaService = new ReCaptchaService();
        $this->customerUidService = new CustomerUidService();
        $this->customerAddressService = new CustomerAddressService();
        $this->customerDayWorkService = new CustomerDayWorkService();
        $this->customerLogoService = new CustomerLogoService();
        $this->customerValidationService = new CustomerValidationService();
        $this->customerUserService = new CustomerUserService();
        $this->customerBtwService = new CustomerBtwService();
        $this->customerDeliveryService = new CustomerDeliveryService();
    }

    public function save()
    {
        $data = request()->all();
        $key = isset($data['key']) && !empty($data['key']) ? $data['key'] : $this->getRandomKey();

        $result = [];
        $this->customerValidationService->check($data, $result);

        if (empty($this->reCaptchaService->checkReCaptcha())) {
            $result["errors"] = [__('login.blockedCaptcha')];
            return response()->json(['errors' => $result["errors"]], 402);
        }

        if (empty($result["errors"])) {
            DB::beginTransaction();
            try {
                $customerId = isset($data['customerId']) && !empty($data['customerId']) ? $data['customerId'] : $this->getNewCustomerId(
                );

                $this->saveCustomerOrEdit($customerId, $data, $key);
                $this->customerAddressService->save($customerId, $data);
                $this->customerDayWorkService->save($customerId, $data['days']);

                $data['saleId'] = !isset($data['saleId']) ? 1 : $data['saleId'];

                if (!empty($data['avatar']) && !empty($data['avatarFileName'])) {
                    $this->customerLogoService->save($customerId, $data['avatar'], $data['avatarFileName']);
                }
                $uid = $this->customerUserService->save($customerId, $data);
                $this->customerDeliveryService->save($uid, $customerId, $data);

                DB::commit();
                $this->eventService->createNewCustomer(
                        $data['customerName'],
                        $key,
                        request()->header('webshop'),
                        $data['password']
                );

                return response()->json(['success' => ["Save success"], 'key' => $key]);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::info(print_r("ERROR 7744477", true));
                \Illuminate\Support\Facades\Log::info(print_r($e->getMessage(), true));

                DB::rollBack();
                return response()->json(['errors' => [$e->getMessage()]], 422);
            }
        } else {
            return response()->json(['errors' => $result["errors"]], 422);
        }
    }

    public function updateCustomerProfilePage()
    {
        $data = request()->all();
        $result = [];
        $customerId = $data['customerId'];
        $this->customerValidationService->check($data, $result);

        if (empty($result["errors"])) {
            DB::beginTransaction();
            try {
                $this->saveCustomerOrEdit($customerId, $data);
                $this->customerAddressService->save($customerId, $data);
                $this->customerDayWorkService->save($customerId, $data['days']);

                if (!empty($data['avatar'])) {
                    $this->customerLogoService->save($customerId, $data['avatar'], $data['avatarFileName']);
                }

                $user = User::where('customerId', $customerId)->first();
                $user->username = $data['username'];
                $user->email = $data['email'];
                $user->save();

                $this->customerDeliveryService->update($user->id, $customerId, $data);

                DB::commit();
                event(new UpdateCustomer($customerId));
                return response()->json(true);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::info(print_r("ERROR 1323456 ", true));
                \Illuminate\Support\Facades\Log::info(print_r($e->getMessage(), true));

                DB::rollBack();
                return response()->json(['message' => [$e->getMessage()]], 422);
            }
        } else {
            return response()->json(['errors' => $result["errors"]], 422);
        }
    }

    public function updateCustomerCart()
    {
        $data = request()->get('customer');
        $data['customerId'] = $this->customerUidService->getCustomerIdUser();
        $result = [];
        $this->customerValidationService->check($data, $result);

        if (empty($result)) {
            DB::beginTransaction();
            try {
                $customer = Customers::firstOrNew(['customerId' => $data['customerId']]);

                $customer->customerName = $data['customerName'];
                $customer->kvk = $data['kvk'];
                $customer->btw = $data['btw'];
                $customer->phone = $data['phone'];
                $customer->emailInvoice = $data['emailInvoice'] ?? '';
                $customer->category = $data['category'];
                $customer->save();

                $this->customerBtwService->checkBTW($data['customerId']);

                $user = User::firstOrNew(['customerId' => $data['customerId']]);
                $user->username = $data['username'];
                $user->email = $data['email'];
                $user->save();

                $this->customerAddressService->save($data['customerId'], $data);

                DB::commit();
                event(new UpdateCustomer($data['customerId']));
                return response()->json(['success' => "Save success"]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['errors' => [$e->getMessage()]], 422);
            }
        } else {
            return response()->json(['errors' => $result['errors']], 422);
        }
    }

    public function saveCustomerOrEdit($customerId, $data, $key = '')
    {
        $customer = Customers::firstOrNew(['customerId' => $customerId]);
        $customer->customerName = $data['customerName'];
        $customer->kvk = $data['kvk'];
        $customer->btw = $data['btw'];
        $customer->emailInvoice = $data['emailInvoice'];
        $customer->category = $data['category'];
        $customer->phone = $data['phone'];
        $customer->phoneMobile = $data['phoneMobile'];
        $customer->mailbox = $data['mailbox'];
        $customer->weekday = (int)$data['weekday'];
        $customer->weekend = (int)$data['weekend'];
        $customer->certainDays = (int)$data['certainDays'];
        $customer->neighbour = (int)$data['neighbour'];
        $customer->status = !empty($customer->status) ? $customer->status : Customers::STATUS['approve'];
//        $customer->regOnPortal = Customers::REG_ON_PORTAL['no'];
        $customer->uidRegister = $this->customerUidService->checkApiUid();

        if (empty($customer->placeRegister)) {
            $customer->placeRegister = !empty(
            request()->header('webshop')
            ) ? StatisticVisits::TYPE_SITE['webshop'] : StatisticVisits::TYPE_SITE['portal'];
        }
        $customer->newsletter = 1;

        if (!empty($key)) {
            $customer->key = $key;
            RegistrationByMail::where('key_url', $key)->update(['status' => Customers::STATUS['filled_in']]);
        }
        $customer->save();
    }

    public function saveCustomerWebshop($orderId)
    {
        try {
            DB::beginTransaction();
            $payInfo = CartOrderPayment::where('orderId', $orderId)->first();
            $data = CartOrderPayment::where('orderId', $orderId)->first()->toArray();

            $customer = new Customers();
            $customer->customerName = $payInfo->customerName;
            $customer->phone = $payInfo->phone;
            $customer->kvk = $payInfo->kvk;
            $customer->btw = $payInfo->btw;
            $customer->email = $payInfo->email;
            $customer->emailInvoice = $payInfo->emailInvoice;
            $customer->category = $payInfo->category;

//        $customer->weekday = (int)$data['weekday'];
//        $customer->weekend = (int)$data['weekend'];
//        $customer->certainDays = (int)$data['certainDays'];
//        $customer->neighbour = (int)$data['neighbour'];

            $customer->status = Customers::STATUS['approve'];
//        $customer->regOnPortal = Customers::REG_ON_PORTAL['no'];
            $customer->uidRegister = $this->customerUidService->checkApiUid();

            if (empty($customer->placeRegister)) {
                $customer->placeRegister = !empty(
                request()->header('webshop')
                ) ? StatisticVisits::TYPE_SITE['webshop'] : StatisticVisits::TYPE_SITE['portal'];
            }
            $customer->newsletter = 1;
            $customer->save();

            $customerId = $customer->customerId;

            $this->customerAddressService->save($customerId, $data);
            $this->customerDayWorkService->save($customerId, []);
            $data['saleId'] = 1;


            $uid = $this->customerUserService->saveUserWebshop($customerId, $data);
            $this->customerDeliveryService->save($uid, $customerId, $data);

            DB::commit();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::info(
                    print_r("ERROR CustomerSaveService WORKFLOW = " . date("Y - m - d H:i:s"), true)
            );
            \Illuminate\Support\Facades\Log::info(print_r($e->getMessage(), true));
            DB::rollBack();
            return response()->json(['errors' => [$e->getMessage()]], 422);
        }
    }


    public function getNewCustomerId()
    {
        $maxValue = Customers::max('customerId');
        return ++$maxValue;
    }

    public function getRandomKey($length = 15)
    {
        $url = '';
        $char = array_merge(range('a', 'z'), range('0', '9'));
        $max = count($char) - 1;
        for ($i = 0; $i < $length; $i++) {
            $randomChar = mt_rand(0, $max);
            $url .= $char[$randomChar];
        }
        return $url;
    }
}