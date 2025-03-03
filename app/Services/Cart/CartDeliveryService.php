<?php

namespace App\Services\Cart;

use App\Actions\FunctionAction;
use App\Http\Resources\Cart\CartDeliveryResource;
use App\Models\CartOrderInfo;
use App\Models\CartOrderPayment;
use App\Models\CartPreset;
use App\Models\CatalogCategory;
use App\Models\CatalogProduct;
use App\Models\Documents;
use App\Models\Log;
use App\Models\TaskOrder;
use App\Services\Coupon\CouponService;
use App\Services\Customer\CustomerDeliveryService;
use App\Services\Customer\CustomerSaveService;
use App\Services\Customer\CustomerService;
use App\Services\Customer\CustomerUidService;
use App\Services\Order\OrderCreoNumService;
use App\Services\Product\ProductCategoryService;
use Illuminate\Support\Facades\DB;
use Exception;

class CartDeliveryService
{

    public const TYPE_DELIVERY_BY_CATEGORY = [

            CatalogCategory::CATEGORY_SERVER => [
                    CartOrderInfo::TYPE_DELIVERY['transport'],  // 2
                    CartOrderInfo::TYPE_DELIVERY['pickup'],   // 3
                    CartOrderInfo::TYPE_DELIVERY['postForeign'],  // 5
            ],

            CatalogCategory::CATEGORY_STORAGE => [
                    CartOrderInfo::TYPE_DELIVERY['transport'],  // 2
                    CartOrderInfo::TYPE_DELIVERY['pickup'],   // 3
                    CartOrderInfo::TYPE_DELIVERY['postForeign'],  // 5
            ],

            CatalogCategory::CATEGORY_WORKSTATION => [
                    CartOrderInfo::TYPE_DELIVERY['postUK'],  // 1
                    CartOrderInfo::TYPE_DELIVERY['transport'],  // 2
                    CartOrderInfo::TYPE_DELIVERY['pickup'],   // 3
                    CartOrderInfo::TYPE_DELIVERY['dropShip'],   // 4
            ],

            CatalogCategory::CATEGORY_LAPTOPS => [
                    CartOrderInfo::TYPE_DELIVERY['postUK'],  // 1
                    CartOrderInfo::TYPE_DELIVERY['pickup'],   // 3
                    CartOrderInfo::TYPE_DELIVERY['dropShip'],   // 4
            ],

            CatalogCategory::CATEGORY_PARTS => [
                    CartOrderInfo::TYPE_DELIVERY['postUK'],  // 1
                    CartOrderInfo::TYPE_DELIVERY['pickup'],   // 3
                    CartOrderInfo::TYPE_DELIVERY['dropShip'],   // 4
            ],

            CatalogCategory::CATEGORY_HDD_SDD => [
                    CartOrderInfo::TYPE_DELIVERY['postUK'],  // 1
                    CartOrderInfo::TYPE_DELIVERY['pickup'],   // 3
                    CartOrderInfo::TYPE_DELIVERY['dropShip'],   // 4
            ],

            CatalogCategory::CATEGORY_SWITCH_ROUTER => [
                    CartOrderInfo::TYPE_DELIVERY['postUK'],  // 1
                    CartOrderInfo::TYPE_DELIVERY['pickup'],   // 3
                    CartOrderInfo::TYPE_DELIVERY['dropShip'],   // 4
            ],

            CatalogCategory::CATEGORY_MONITORS => [
                    CartOrderInfo::TYPE_DELIVERY['postUK'],  // 1
                    CartOrderInfo::TYPE_DELIVERY['pickup'],   // 3
                    CartOrderInfo::TYPE_DELIVERY['dropShip'],   // 4
            ],

    ];

    public CustomerUidService $customerUidService;
    public OrderCreoNumService $orderCreoNumService;
    public CustomerService $customerService;
    public CartCustomerService $cartCustomerService;
    public ProductCategoryService $productCategoryService;
    public CustomerDeliveryService $customerDeliveryService;
    public FunctionAction $func;
    public CustomerSaveService $customerSaveService;

    function __construct()
    {
        $this->customerUidService = new CustomerUidService();
        $this->orderCreoNumService = new OrderCreoNumService();
        $this->customerService = new CustomerService();
        $this->cartCustomerService = new CartCustomerService();
        $this->func = new FunctionAction();
        $this->productCategoryService = new ProductCategoryService();
        $this->customerDeliveryService = new CustomerDeliveryService();
        $this->customerSaveService = new CustomerSaveService();
    }


    public function get($orderId)
    {
        if (!empty($orderId)) {
            $cartInfo = CartOrderInfo::where('orderId', $orderId)->first();
            if (isset($cartInfo->orderId)) {
                return new CartDeliveryResource($cartInfo);
            }
        }
    }

    public function getDeliveryPermission($orderId)
    {
        $products = CartPreset::where('orderId', $orderId)->get();

        $deliveryPermission = [];
        $findServer = false;
        $findParts = false;

        foreach ($products as $key => $product) {
            $categoryMain = $this->productCategoryService->getCategoryMain($product->product->category);

            if ($categoryMain == CatalogCategory::CATEGORY_SERVER || $categoryMain == CatalogCategory::CATEGORY_STORAGE) {
                $findServer = true;
            }

            if ($categoryMain == CatalogCategory::CATEGORY_PARTS ||
                    $categoryMain == CatalogCategory::CATEGORY_HDD_SDD ||
                    $categoryMain == CatalogCategory::CATEGORY_SWITCH_ROUTER) {
                $findParts = true;
            }

            if (isset(self::TYPE_DELIVERY_BY_CATEGORY[$categoryMain])) {
                if (!empty($deliveryPermission)) {
                    $deliveryPermission = array_uintersect(
                            $deliveryPermission,
                            self::TYPE_DELIVERY_BY_CATEGORY[$categoryMain],
                            "strcasecmp"
                    );
                } else {
                    $deliveryPermission = self::TYPE_DELIVERY_BY_CATEGORY[$categoryMain];
                }
            }
        }

        if ($findServer && $findParts) {
            $deliveryPermission = [
                    CartOrderInfo::TYPE_DELIVERY['transport'],  // 2
                    CartOrderInfo::TYPE_DELIVERY['pickup'],   // 3
                    CartOrderInfo::TYPE_DELIVERY['dropShip'],   // 4
                    CartOrderInfo::TYPE_DELIVERY['postForeign'],  // 5
            ];
        }

        return $deliveryPermission;
    }

    public function getDeliveryPermissionOffer($products)
    {
        $deliveryPermission = [];
        $findServer = false;
        $findParts = false;

        foreach ($products as $key => $product) {

            $product = CatalogProduct::where('productId', $product['id'])->first();

            $categoryMain = $this->productCategoryService->getCategoryMain($product->category);

            if ($categoryMain == CatalogCategory::CATEGORY_SERVER || $categoryMain == CatalogCategory::CATEGORY_STORAGE) {
                $findServer = true;
            }

            if ($categoryMain == CatalogCategory::CATEGORY_PARTS ||
                    $categoryMain == CatalogCategory::CATEGORY_HDD_SDD ||
                    $categoryMain == CatalogCategory::CATEGORY_SWITCH_ROUTER) {
                $findParts = true;
            }

            if (isset(self::TYPE_DELIVERY_BY_CATEGORY[$categoryMain])) {
                if (!empty($deliveryPermission)) {
                    $deliveryPermission = array_uintersect(
                            $deliveryPermission,
                            self::TYPE_DELIVERY_BY_CATEGORY[$categoryMain],
                            "strcasecmp"
                    );
                } else {
                    $deliveryPermission = self::TYPE_DELIVERY_BY_CATEGORY[$categoryMain];
                }
            }
        }

        if ($findServer && $findParts) {
            $deliveryPermission = [
                    CartOrderInfo::TYPE_DELIVERY['transport'],  // 2
                    CartOrderInfo::TYPE_DELIVERY['pickup'],   // 3
                    CartOrderInfo::TYPE_DELIVERY['dropShip'],   // 4
                    CartOrderInfo::TYPE_DELIVERY['postForeign'],  // 5
            ];
        }

        return $deliveryPermission;
    }

    public function store(): \Illuminate\Http\JsonResponse|bool
    {
        $data = request()->all();

        $uid = $this->customerUidService->checkApiUid();
        Log::saveLog(Log::TYPE['cartDelivery'], ['data' => $data], $uid, $data['orderId']);

        DB::beginTransaction();
        try {
            if (!isset($data['type']) || empty($data['type'])) {
                return false;
            }

            $taskOrder = TaskOrder::with(['info', 'payment'])
                    ->where('orderId', $data['orderId'])
                    ->first();

            if (!empty($taskOrder)) {
                throw new Exception('The order is blocked');
            }


            $cartProductService = new CartProductService();
            $cartProductService->checkQuantityOrder($data['orderId']);

            $couponService = new CouponService($data['orderId']);

            $cartInfo = CartOrderInfo::firstOrNew(['orderId' => $data['orderId']]);
            $pay = CartOrderPayment::firstOrNew(['orderId' => $data['orderId']]);
            $couponService->checkDeliveryCoupon(
                    $cartInfo,
                    $data['type'],
                    in_array('quickly', $data['checkboxes'])
            );

            $cartInfo->delivery = $data['type'];
//                $cartInfo->orderTypeId = Documents::FACTUUR;

            if (request()->header('webshop')) {
                $customerRegister = $this->cartCustomerService->checkCustomerRegisterOrder($data['orderId']);

                if (empty($customerRegister)) {
                    $this->customerSaveService->saveCustomerWebshop($data['orderId']);
                    $customerRegister = $this->cartCustomerService->checkCustomerRegisterOrder($data['orderId']);
                }

                if (!empty($customerRegister)) {
                    $uid = $customerRegister->uid;
//                    $cartInfo->uid = $customerRegister->uid;
                    $cartInfo->customerId = $customerRegister->customerId;
                }
            }

            if ($cartInfo->orderTypeId == Documents::FACTUUR) {
                $cartInfo->orderMask = $this->cartCustomerService->orderCountCustomer($uid);
            }

            $customer = $this->customerService->getCustomer();
            $customerInfo = $this->checkCustomerForDelivery($customer, $pay);

            $cartInfo = $this->checkAddress($cartInfo, $data['form'], $customerInfo);
            $cartInfo->date = $data['form']['myDate'] ?? '';
            $cartInfo->timeStart = $data['form']['timeStart'] ?? '';
            $cartInfo->timeFinish = $data['form']['timeFinish'] ?? '';
            $cartInfo->weekday = in_array('weekday', $data['checkboxes']);
            $cartInfo->weekend = in_array('weekend', $data['checkboxes']);
            $cartInfo->neighbour = in_array('neighbour', $data['checkboxes']);
            $cartInfo->quickly = in_array('quickly', $data['checkboxes']);
            $cartInfo->coordinate_latitude = '';
            $cartInfo->coordinate_height = '';
            $cartInfo->placeOrder = request()->header('webshop') ? CartOrderInfo::PLACE_WEBSHOP : CartOrderInfo::PLACE_PORTAL;
            $cartInfo->save();

            $this->customerDeliveryService->saveCartDelivery($cartInfo);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::info(print_r(" ERROR Cart CartDeliveryService store", true));
            \Illuminate\Support\Facades\Log::info(print_r($e->getMessage(), true));
            return response()->json(['errors' => [$e->getMessage()]], 422);
        }
    }


    public function checkCustomerForDelivery($customer, $pay): array
    {
        $result = [];

        $result['username'] = $customer->username ?? $pay->username;
        $result['customerName'] = $customer->customerName ?? $pay->customerName;
        $result['address'] = $customer->address ?? $pay->address;
        $result['house'] = $customer->house ?? $pay->house;
        $result['postcode'] = $customer->postcode ?? $pay->postcode;
        $result['region'] = $customer->region ?? $pay->region;
        $result['country'] = $customer->country ?? $pay->country;
        $result['email'] = $customer->email ?? $pay->email;
        $result['phone'] = $customer->phone ?? $pay->phone;
        return $result;
    }

    public function checkAddress($cartInfo, $data, $customerInfo)
    {
        if ($cartInfo->delivery == CartOrderInfo::TYPE_DELIVERY['pickup']) {
            $cartInfo->customerName = $customerInfo['customerName'];
            $cartInfo->username = $customerInfo['username'];
            $cartInfo->namens = $customerInfo['namens'] ?? '';
            $cartInfo->address = $customerInfo['address'];
            $cartInfo->house = $customerInfo['house'];
            $cartInfo->postcode = $customerInfo['postcode'];
            $cartInfo->region = $customerInfo['region'];
            $cartInfo->country = $customerInfo['country'];
            $cartInfo->email = $customerInfo['email'];
            $cartInfo->phone = $customerInfo['phone'];
        } else {
            $cartInfo->customerName = $this->func->check(
                    $data['customerName']
            ) ? $data['customerName'] : $customerInfo['customerName'];
            $cartInfo->username = $this->func->check($data['username']) ? $data['username'] : $customerInfo['username'];
            $cartInfo->namens = !empty($data['namens']) && isset($data['namens']) ? $data['namens'] : '';
            $cartInfo->address = $this->func->check($data['address']) ? $data['address'] : $customerInfo['address'];
            $cartInfo->house = $this->func->check($data['house']) ? $data['house'] : $customerInfo['house'];
            $cartInfo->postcode = $this->func->check($data['postcode']) ? $data['postcode'] : $customerInfo['postcode'];
            $cartInfo->region = $this->func->check($data['region']) ? $data['region'] : $customerInfo['region'];
            $cartInfo->country = $this->func->check($data['country']) ? $data['country'] : $customerInfo['country'];
            $cartInfo->email = $this->func->check($data['email']) ? $data['email'] : $customerInfo['email'];
            $cartInfo->phone = $this->func->check($data['phone']) ? $data['phone'] : $customerInfo['phone'];
        }
        return $cartInfo;
    }

    public function getDeliveryRatio($orderId)
    {
        $products = CartPreset::where('orderId', $orderId)->get();
        $ratio = 0;

        foreach ($products as $key => &$product) {
            $categoryMain = $this->productCategoryService->getCategoryMain($product->product->category);

            if ($categoryMain == CatalogCategory::CATEGORY_SERVER || $categoryMain == CatalogCategory::CATEGORY_STORAGE) {
                return 1;
            }

            if ($categoryMain == CatalogCategory::CATEGORY_WORKSTATION ||
                    $categoryMain == CatalogCategory::CATEGORY_LAPTOPS ||
                    $categoryMain == CatalogCategory::CATEGORY_SWITCH_ROUTER ||
                    $categoryMain == CatalogCategory::CATEGORY_MONITORS) {
                $ratio += $product->quantity;
            }
        }

        return $ratio == 0 ? 1 : $ratio;
    }

    public function getDeliveryRatioOffer($products)
    {
        $ratio = 0;

        foreach ($products as $product) {
            $productInBase = CatalogProduct::where('productId', $product['id'])->first();
            $categoryMain = $this->productCategoryService->getCategoryMain($productInBase->category);

            if ($categoryMain == CatalogCategory::CATEGORY_SERVER || $categoryMain == CatalogCategory::CATEGORY_STORAGE) {
                return 1;
            }

            if ($categoryMain == CatalogCategory::CATEGORY_WORKSTATION ||
                    $categoryMain == CatalogCategory::CATEGORY_LAPTOPS ||
                    $categoryMain == CatalogCategory::CATEGORY_SWITCH_ROUTER ||
                    $categoryMain == CatalogCategory::CATEGORY_MONITORS) {
                $ratio += $product['quantity'];
            }
        }

        return $ratio == 0 ? 1 : $ratio;
    }
}