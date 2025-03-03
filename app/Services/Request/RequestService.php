<?php

namespace App\Services\Request;

use App\Events\AdminDataEvent;
use App\Events\NewLifeLineCustomer;
use App\Events\UpdateOrderUser;
use App\Models\CatalogCategory;
use App\Models\Message;
use App\Models\RequestOffer;
use App\Models\Settings;
use App\Services\Customer\CustomerUidService;
use App\Services\Event\EventService;
use Illuminate\Support\Facades\DB;

class RequestService
{

    public CustomerUidService $customerUidService;
    public EventService $eventService;


    function __construct()
    {
        $this->customerUidService = new CustomerUidService();
        $this->eventService = new EventService();
    }


    public function index()
    {
        $query = DB::table('request_offer', 'ro')
                ->leftJoin('users as u', 'u.id', '=', 'ro.uid')
                ->join('catalog_category as cc', 'cc.categoryId', '=', 'ro.categoryId')
                ->selectRaw('cc.categoryName')
                ->selectRaw('ro.id')
                ->selectRaw('ro.time')
                ->selectRaw('u.username')
                ->selectRaw('ro.title')
                ->orderBy('time', 'desc');

        $uid = $this->customerUidService->checkApiUid();

        if (empty($this->customerUidService->support())) {
            $query->where('ro.uid', '=', $uid);
        }

        $requests = $query->get();

        if (!empty($requests)) {
            foreach ($requests as $request) {

                $request->last = DB::table('message', 'm')
                        ->leftJoin('users as u', 'u.id', '=', 'm.uid')
                        ->select('m.read')
                        ->selectRaw('m.time')
                        ->selectRaw('u.username')
                        ->selectRaw('m.support')
                        ->selectRaw('m.message')
                        ->where('m.value', $request->id)
                        ->where('m.type', Message::MESSAGE_TYPE['request'])
                        ->latest('m.created_at')
                        ->first();
            }
        }
        $result["requests"] = $requests;
        $result["categories"] = $this->getMainCategories();

        return $result;
    }

    public function getMainCategories()
    {
        return DB::table('catalog_category')
                ->selectRaw('categoryName as label')
                ->selectRaw('categoryId')
                ->where('parentId', '=', '0')
                ->whereNotIn('categoryId', [CatalogCategory::CATEGORY_NO_PUBLIC_SALE])
                ->get();
    }


    public function show($requestId)
    {
        $query = DB::table('request_offer', 'ro')
                ->join('catalog_category as cc', 'cc.categoryId', '=', 'ro.categoryId')
                ->selectRaw('ro.id')
                ->selectRaw('ro.title')
                ->selectRaw('ro.description')
                ->selectRaw('ro.time as date')
                ->selectRaw('cc.categoryName')
                ->where('ro.id', '=', $requestId);

        $uid = $this->customerUidService->checkApiUid();

        if (empty($this->customerUidService->support())) {
            $query->where('ro.uid', '=', $uid);
        }

        return $query->first();
    }

    public function store()
    {
        $time = time();
        $data = request()->all();
        $uid = $this->customerUidService->checkApiUid();

        $item = new RequestOffer();
        $item->uid = $uid;
        $item->categoryId = $data['category'];
        $item->title = $data['title'];
        $item->description = $data['description'];
        $item->status = RequestOffer::STATUS['open'];
        $item->time = $time;
        $item->save();

        $support = $this->customerUidService->support();

        $message = new Message();
        $message->value = $item->id;
        $message->type = Message::MESSAGE_TYPE['request'];
        $message->uid = $uid;
        $message->support = !empty($support);
        $message->message = $data['description'];
        $message->time = $time;
        $message->save();

        $this->eventService->createNewRequest($item->id);

        event(new UpdateOrderUser($uid));
        event(new NewLifeLineCustomer($uid));

        if (!$support) {
            event(new AdminDataEvent(Settings::ADMIN_DATA_TYPE['request_new'], $item));
        }

        return $item;
    }
}