<?php

namespace App\Services\Coupon;

use App\Models\CartOrderInfo;
use App\Models\CartOrderPayment;
use App\Models\Coupon;
use App\Models\Settings;
use App\Services\Cart\CartDeliveryService;
use App\Services\Setting\SettingService;

class CouponService
{

    public $orderId;
    public CartDeliveryService $cartDeliveryService;

    public function __construct($orderId = '')
    {
        $this->orderId = $orderId;
        $this->cartDeliveryService = new CartDeliveryService();
    }

    public function adoptedCoupon(&$data, &$info)
    {
        $coupon = Coupon::where('coupon', $info->coupon)->first();
        $data['adoptedCoupon'] = true;
        $data['couponType'] = $coupon->type;

        switch ($coupon->type) {
            case Coupon::COUPON_TYPE_PERSONAL:
                if (auth()->user()->role != 0) {
                    $this->simpleRebuildCoupon($data, $coupon->percent);
                }
                break;
            case Coupon::COUPON_TYPE_SIMPLE:
            case Coupon::COUPON_TYPE_BATCH:
            case Coupon::COUPON_TYPE_FACTUUR:
            case Coupon::COUPON_TYPE_FACTUUR_NULL:
                $this->simpleRebuildCoupon($data, $coupon->percent);
                break;
            case Coupon::COUPON_TYPE_EURO:
            case Coupon::COUPON_TYPE_INRUIL:
            case Coupon::COUPON_TYPE_ONDERDELEN:
            case Coupon::COUPON_TYPE_DIVERSE:
                $this->euroRebuildCoupon($data, $coupon->value);
                break;
            case Coupon::COUPON_TYPE_GEEN_BTW:
                $this->ndsRebuildCoupon($data);
                break;
            case Coupon::COUPON_TYPE_TRANSPORT:
                $this->transportRebuildCoupon($data, $info);
                break;
            case Coupon::COUPON_TYPE_KOSTEN:
                $this->kostenRebuildCoupon($data, $coupon, $info);
                break;
            case Coupon::COUPON_TYPE_QUICKLY:
                $this->quicklyRebuildCoupon($data, $coupon, $info);
                break;
            case Coupon::COUPON_TYPE_TRANSACTION:
                //another place
                $this->transactionRebuildCoupon($data, $info);
                break;
            default:
                break;
        }
    }

    public function simpleRebuildCoupon(&$data, $percent)
    {
        $data['discountCouponPercent'] = $percent;
        $data['priceDiscountCoupon'] = $data['priceFullDiscountAfter'] * ((int)$percent / 100);
        $data['priceFullDiscountAfter'] = round(($data['priceFullDiscountAfter'] - $data['priceDiscountCoupon']), 2);
        $this->saveDiscountCoupon($data['priceDiscountCoupon']);
    }


    public function euroRebuildCoupon(&$data, $euro)
    {
        $data['discountCouponEURO'] = $euro;
        $data['priceDiscountCoupon'] = $euro;
        $data['priceFullDiscountAfter'] = round(($data['priceFullDiscountAfter'] - $data['priceDiscountCoupon']), 2);

        if ($data['priceFullDiscountAfter'] < 0) {
            $data['priceDiscountCoupon'] = round(
                ($data['priceDiscountCoupon'] - abs($data['priceFullDiscountAfter'])),
                2
            );
            $data['priceFullDiscountAfter'] = 0;
        }
        $this->saveDiscountCoupon($data['priceDiscountCoupon']);
    }


    public function ndsRebuildCoupon(&$data)
    {
        $data['priceDiscountCoupon'] = round(($data['priceFullDiscountAfter'] * $data['nds'] / 100), 2);
        $data['nds'] = 0;
        $this->saveDiscountCoupon($data['priceDiscountCoupon']);
    }


    public function transportRebuildCoupon(&$data, &$info)
    {
        if ($info->delivery == CartOrderInfo::TYPE_DELIVERY['transport']) {
//            $data['priceDiscountCoupon'] = $data['transport'];
        }

        $data['transport'] = 0;
        $this->saveDiscountCoupon($data['priceDiscountCoupon']);
    }


    public function kostenRebuildCoupon(&$data, $coupon, &$info)
    {
        $data['postUK'] -= $coupon->value;
        $data['postUK'] = $data['postUK'] < 0 ? 0 : $data['postUK'];

        if ($info->delivery == CartOrderInfo::TYPE_DELIVERY['postUK']) {
            $data['priceDiscountCoupon'] = $coupon->value;
        }
        $this->saveDiscountCoupon($data['priceDiscountCoupon']);
    }


    public function quicklyRebuildCoupon(&$data, $coupon, &$info)
    {
        $data['quickly'] -= $coupon->value;
        $data['quickly'] = $data['quickly'] < 0 ? 0 : $data['quickly'];

        if (!empty($info->quickly)) {
            $data['priceDiscountCoupon'] = $coupon->value;
        }
        $this->saveDiscountCoupon($data['priceDiscountCoupon']);
    }

    public function transactionRebuildCoupon(&$data, &$info)
    {
        $coupon = Coupon::where('coupon', $info->coupon)->first();
        $payInfo = CartOrderPayment::where('orderId', '=', $this->orderId)->first();

        if (!empty($coupon) && $coupon->type == Coupon::COUPON_TYPE_TRANSACTION && !empty($payInfo)
            && in_array($payInfo->method, [
                CartOrderPayment::METHOD_PAY_BANCONTACT,
                CartOrderPayment::METHOD_PAY_BELFIUS,
                CartOrderPayment::METHOD_PAY_KBC,
                CartOrderPayment::METHOD_PAY_GIROPAY,
                CartOrderPayment::METHOD_PAY_SOFORT,
            ])) {
            $data['priceTransaction'] -= $coupon->value;
            $data['rateBank'] -= $coupon->value;
            $data['rateBank'] = $data['rateBank'] < 0 ? 0 : $data['rateBank'];
            $data['priceDiscountCoupon'] = $coupon->value;
            $info->discountCoupon = $coupon->value;
            $info->save();
        }
    }

    public function saveDiscountCoupon($priceDiscountCoupon)
    {
        CartOrderInfo::where('orderId', $this->orderId)->update(['discountCoupon' => $priceDiscountCoupon]);
    }

    public function checkStaffelCoupon($info)
    {
        $percent = 0;
        if (!empty($info) && !empty($info->coupon)) {
            $coupon = Coupon::where('coupon', $info->coupon)->first();
            if (!empty($coupon)) {
                if ($coupon->type == Coupon::COUPON_TYPE_STAFFEL) {
                    $percent = $coupon->percent;
                }
            } else {
                $info->coupon = '';
                $info->save();
            }
        }
        return $percent;
    }

    public function checkDeliveryCoupon(&$cartInfo, $type, $isQuickly)
    {
        $coupon = '';
        if ($cartInfo->coupon) {
            $coupon = Coupon::where('coupon', $cartInfo->coupon)->first();
        }

        $settingService = new SettingService();

        $ratio = $this->cartDeliveryService->getDeliveryRatio($cartInfo->orderId);

        if (Settings::PRICE_TYPE_DELIVERY[$type]) {
            if ($type == Settings::TRANSPORT || $type == Settings::POST_FOREIGN) {
                $ratio = 1;
            }

            $priceTypeDelivery = $ratio * $settingService->get(Settings::PRICE_TYPE_DELIVERY[$type]);
        } else {
            $priceTypeDelivery = 0;
        }

        if ($isQuickly) {
            $priceQuickly = $settingService->get('quickly');
            if (!empty($coupon)) {
                if ($coupon->type == Coupon::COUPON_TYPE_QUICKLY) {
                    $this->saveDiscountCoupon($priceQuickly);
                    $priceQuickly = 0;
                }
            }
        } else {
            $priceQuickly = 0;
        }


        if (!empty($coupon)) {
            if ($coupon->type == Coupon::COUPON_TYPE_KOSTEN && $type == 1 ||
                $coupon->type == Coupon::COUPON_TYPE_TRANSPORT && $type == 2) {
                $this->saveDiscountCoupon($coupon->value);
                $priceTypeDelivery = 0;
//                $priceTypeDelivery = $priceTypeDelivery < 0 ? 0 : $priceTypeDelivery;
            }
        }

        $cartInfo->priceTypeDelivery = (float)$priceTypeDelivery;
        $cartInfo->priceExtraDelivery = $priceQuickly;
        $cartInfo->priceDelivery = (float)$priceQuickly + (float)$priceTypeDelivery;
    }


    public function transactionRebuildCouponClear(&$info)
    {
        if (isset($info->coupon) && !empty($info->coupon)) {
            $coupon = Coupon::where('coupon', $info->coupon)->first();
            if (!empty($coupon) && $coupon->type == Coupon::COUPON_TYPE_TRANSACTION) {
                $info->discountCoupon = 0;
                $info->save();
            }
        }
    }

    public function changeStatusCoupon($status = Coupon::COUPON_STATUS['open'])
    {
        $orderInfo = CartOrderInfo::where('orderId', '=', $this->orderId)->first();

        if (!empty($orderInfo) && !empty($orderInfo->coupon)) {
            Coupon::where('coupon', $orderInfo->coupon)->update(['status' => $status]);
        }
    }

    public function checkCartCoupon($interCoupon)
    {
        $coupon = '';
        $orderInfo = CartOrderInfo::where('orderId', $this->orderId)
                ->whereIn('status', [
                        CartOrderInfo::STATUS_ORDER['open'],
                        CartOrderInfo::STATUS_ORDER['waiting_payment']
                ])
                ->first();

        if (!empty($orderInfo)) {
            if (!empty($interCoupon)) {
                $start = date("Y-m-d");
                $coupon = Coupon::where(function ($query) use ($start) {
                    $query->where('finish', '>=', $start);
                    $query->orWhere('endless', '=', 1);
                })
                    ->where('status', Coupon::COUPON_STATUS['open'])
                    ->where('coupon', $interCoupon)
                    ->whereIn('type', [
                        Coupon::COUPON_TYPE_SIMPLE,
                        Coupon::COUPON_TYPE_BATCH,
                        Coupon::COUPON_TYPE_EURO,
                        Coupon::COUPON_TYPE_TRANSPORT,
                        Coupon::COUPON_TYPE_KOSTEN,
                        Coupon::COUPON_TYPE_QUICKLY,
                        Coupon::COUPON_TYPE_TRANSACTION,
                        Coupon::COUPON_TYPE_PERSONAL,
                        Coupon::COUPON_TYPE_FACTUUR,
                        Coupon::COUPON_TYPE_PERSONAL,
                        Coupon::COUPON_TYPE_FACTUUR_NULL,
                        Coupon::COUPON_TYPE_GEEN_BTW,
                        Coupon::COUPON_TYPE_ONDERDELEN,
                        Coupon::COUPON_TYPE_DIVERSE,
                    ])
                    ->first();
            }

            if ((!empty($coupon) && $coupon->type == Coupon::COUPON_TYPE_PERSONAL && auth()->user()->role == 0)) {
                return;
            }

            $cartInfo = CartOrderInfo::firstOrNew(['orderId' => $this->orderId]);
            $cartInfo->coupon = !empty($coupon) ? $coupon->coupon : '';
            $cartInfo->discountCoupon = 0;
            $cartInfo->save();
        }
    }

}
