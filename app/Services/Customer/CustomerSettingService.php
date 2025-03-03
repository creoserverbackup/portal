<?php

namespace App\Services\Customer;

use App\Http\Resources\Customer\CustomerSettingResource;
use App\Models\Customers;
use App\Models\File;
use App\Models\UserSettings;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CustomerSettingService
{
    public CustomerUidService $customerUidService;

    function __construct()
    {
        $this->customerUidService = new CustomerUidService();
    }

    public function get()
    {
        return (new CustomerSettingResource(UserSettings::getUserSetting()))
                ->additional(['data' => [ "backgroundUrl" => $this->getBackground()]]);
    }

    public function save()
    {
        $data = request()->all();
        $item = Customers::where('key', $data['key'])->first();

        if (!empty($item)) {
            $user = User::where('customerId', $item->customerId)->first();
            $this->saveSettings($data, $user['id']);

            if (!empty($data['background']) && !empty($data['backgroundUrl'])) {
                $this->saveBackground($item->customerId, $data['backgroundUrl']);
            }

            return response()->json(true);
        } else {
            return response()->json('Customer not found', 422);
        }
    }

    public function store()
    {
        DB::beginTransaction();
        try {
            $data = request()->json()->all();

            $uid = $this->customerUidService->checkApiUid();
            $this->saveSettings($data, $uid);

            if (!empty($data['background']) && !empty($data['backgroundUrl'])) {
                $this->saveBackground(Auth()->user()->customerId, $data['backgroundUrl']);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }
        return $this->get();
    }

    public function saveSettings($data, $uid)
    {
        $model = UserSettings::firstOrNew(['uid' => $uid]);
        $model->uid = $uid;
        $model->sound = $data['sound'];
        $model->sendEmail = $data['sendEmail'];
        $model->googlePushMessages = $data['googlePushMessages'];
        $model->importantThings = $data['importantThings'];
        $model->deliveryServiceTracking = $data['deliveryServiceTracking'];
        $model->darkMode = $data['darkMode'];
        $model->classicMode = $data['classicMode'];
        $model->lifeLine = $data['lifeLine'];
        $model->background = $data['background'];
        $model->save();
    }

    public function saveBackground($customerId, $url)
    {
        $fileName = $customerId . '-' . rand(0, 10000) . '-background.jpeg';
        Storage::disk('sftpFiles')->put($fileName, file_get_contents($url), 'public');
        $file = File::firstOrNew([
                'value' => $customerId,
                'type' => File::FILE_TYPE['customer_background']
        ]);

        if (!empty($file->disk_name)) {
            Storage::disk('sftpFiles')->delete($file->disk_name);
        }

        $path = Storage::disk('sftpFiles')->url($fileName);
        $size = Storage::disk('sftpFiles')->size($fileName);

        $file->value = $customerId;
        $file->type = File::FILE_TYPE['customer_background'];
        $file->path = $path;
        $file->disk_name = $fileName;
        $file->file_name = $fileName;
        $file->file_size = $size;
        $file->save();
    }

    public function getBackground()
    {
        $customerId = $this->customerUidService->getCustomerIdUser();
        $backgroundUrl = File::where('value', $customerId)->where('type', File::FILE_TYPE['customer_background'])->first();
        return !empty($backgroundUrl) ? $backgroundUrl->path : '';
    }

}