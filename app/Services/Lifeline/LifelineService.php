<?php

namespace App\Services\Lifeline;

use App\Events\NewLifeLineCustomer;
use App\Models\CartPreset;
use App\Models\Chat;
use App\Models\LifeLine;
use App\Models\RMA;
use App\Models\Settings;
use App\Models\StatisticVisits;
use App\Models\Ticket;
use App\Models\User;
use App\Services\Customer\CustomerUidService;
use App\Services\Event\EventAdminDataService;
use App\Services\Order\OrderCreoNumService;
use Illuminate\Support\Facades\DB;

class LifelineService
{

    public OrderCreoNumService $orderCreoNumService;
    public CustomerUidService $customerUidService;
    public EventAdminDataService $eventAdminDataService;

    public function __construct(
    )
    {
        $this->orderCreoNumService = new OrderCreoNumService();
        $this->customerUidService = new CustomerUidService();
        $this->eventAdminDataService = new EventAdminDataService();
    }

    public function index()
    {
        return LifeLine::where('uid', $this->customerUidService->checkApiUid())
                ->where('status', LifeLine::STATUS_LIFE_LINE['open'])
                ->where('site', StatisticVisits::TYPE_SITE['portal'])
                ->where('archive_at', '=', '')
                ->orderBy('view', 'desc')
                ->latest('updated_at')
                ->get();
    }

    public function get()
    {
        return LifeLine::where('uid', '=', $this->customerUidService->checkApiUid())
                ->where('status', '=', LifeLine::STATUS_LIFE_LINE['open'])
                ->where('archive_at', '=', '')
                ->orderBy('view', 'desc')
                ->latest('updated_at')
                ->get();
    }

    public function getType($type, $value)
    {
        LifeLine::where('type', $type)->where('value', $value)->update(['view' => 0]);
        event(new NewLifeLineCustomer($this->customerUidService->checkApiUid()));

        switch ($type) {
            case LifeLine::TYPE_LIFELINE['order']:
                return $this->getLifeLineOrders($value);
            case LifeLine::TYPE_LIFELINE['rma']:
                return $this->getLifeLineRMA($value);
            case LifeLine::TYPE_LIFELINE['chat']:
                return $this->getLifeLineLiveChat($value);
            case LifeLine::TYPE_LIFELINE['ticket']:
                return $this->getLifeLineTicket($value);
            case LifeLine::TYPE_LIFELINE['request']:
                return $this->getLifeLineRequest($value);
            default:
                break;
        }

        return response()->json(true);
    }

    public function getLifeLineTicket($id)
    {
        return Ticket::find($id);
    }

    public function getLifeLineOrders($orderId)
    {
        $info = DB::table('cart_order_info', 'coi')
                ->join('order_types as ot', 'ot.orderTypeId', '=', 'coi.orderTypeId')
                ->join('cart_order_payment as cop', 'cop.orderId', '=', 'coi.orderId')
                ->join('tasks_orders as to', 'to.orderId', '=', 'coi.orderId')
                ->selectRaw('coi.orderId')
                ->selectRaw('ot.typeSign')
                ->selectRaw('coi.customerId')
                ->selectRaw('coi.orderMask')
                ->selectRaw('to.status')
                ->selectRaw('to.date')
                ->where('coi.orderId', $orderId)
                ->first();

        if (!empty($info)) {
            $info->orderNumber = $this->orderCreoNumService->get($orderId);
            $info->description = $this->getNameOrder($orderId);
        }
        return $info;
    }

    public function getNameOrder($orderId)
    {
        $products = CartPreset::where('orderId', '=', $orderId)->get();

        $title = [];

        foreach ($products as $key => $product) {
            $line = '<strong>Product №' . ++$key . ': ' . $this->getNameProduct($product->productId) . '</strong></br> ';

            $configs = isset($product->configuration) ? unserialize($product->configuration) : '';
            if (!empty($configs)) {
                foreach ($configs as $config) {
                    if ($config['quantity'] > 0) {
                        $line .= $config['label'] . ' ' . $config['quantity'] . 'x ' . $config['name']  . ' </br> ' ;
                    }
                }
            }
            $title[] = $line;
        }
        return $title;
    }

    public function getNameProduct($productId): string
    {
        $productInfo = DB::table('catalog_product', 'cp')
                ->join('catalog_mark as cm', 'cm.markId', '=', 'cp.mark')
                ->selectRaw('cm.markName')
                ->selectRaw('cp.name')
                ->where('cp.productId', '=', $productId)
                ->first();

        return $productInfo->markName . ' ' . $productInfo->name;
    }

    public function getLifeLineRMA($orderId)
    {
        $rma = RMA::where('orderRma', $orderId)->first();
        if (!empty($rma)) {
            $rma->orderNumber = $this->orderCreoNumService->get($orderId);
        }
        return $rma;
    }

    public function getLifeLineLiveChat($id)
    {
        return Chat::where('id', $id)
                ->where('status', '=', Chat::STATUS_CHAT['open'])
                ->first();
    }

    public function updateLifeLineCustomer($customer, $date)
    {
        $user = User::where('customerId', '=', $customer["customerId"])->first();
        $data = [
              'type' => LifeLine::TYPE_LIFELINE['social'],
              'date' => date("Y-m-d H:i:s"),
              'title' => $date['title'] . $customer['customerName'],
              'author' => $date['author'] . $user->username,
        ];

        $this->eventAdminDataService->send(Settings::ADMIN_DATA_TYPE['lifeline_new'], $data);
    }


    public function getLifeLineRequest($id)
    {
        return DB::table('request_offer', 'ro')
                ->join('catalog_category as cc', 'cc.categoryId', '=', 'ro.categoryId')
                ->selectRaw('ro.id')
                ->selectRaw('ro.title')
                ->selectRaw('ro.description')
                ->selectRaw('ro.created_at')
                ->selectRaw('ro.status')
                ->selectRaw('cc.categoryName')
                ->where('ro.id', '=', $id)
                ->first();
    }

    public function delete($id)
    {
        if ($id == 'all') {
            $ids = $this->getIdsAllLifeline();
            return LifeLine::whereIn('id', $ids)->delete();
        } else {
            return LifeLine::where('id', $id)->delete();
        }
    }

    public function getIdsAllLifeline()
    {
        return LifeLine::where('uid', $this->customerUidService->checkApiUid())
                ->where('status', LifeLine::STATUS_LIFE_LINE['open'])
                ->where('site', StatisticVisits::TYPE_SITE['portal'])
                ->where('archive_at', '=', '')
                ->orderBy('view', 'desc')
                ->latest('updated_at')
                ->pluck('id');
    }
}