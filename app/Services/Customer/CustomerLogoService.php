<?php

namespace App\Services\Customer;

use App\Models\File;
use Illuminate\Support\Facades\Storage;

class CustomerLogoService
{

    public function save($customerId, $avatar, $avatarFileName)
    {
        $time = time();
        $fileName = $customerId . '-logo-' . $avatarFileName;
        list($type, $avatar) = explode(';', $avatar);
        list(, $avatar) = explode(',', $avatar);
        $avatar = base64_decode($avatar);

        if (!empty($avatar)) {

            $logo = File::firstOrNew([
                    'value' => $customerId,
                    'type' => File::FILE_TYPE['customer_logo']
            ]);

            if (!empty($logo->disk_name)) {
                if (Storage::disk('sftpFiles')->exists($logo->disk_name)) {
                    Storage::disk('sftpFiles')->delete($logo->disk_name);
                }
            }

            Storage::disk('sftpFiles')->put($fileName, $avatar, 'public');

            $path = Storage::disk('sftpFiles')->url($fileName);
            $size = Storage::disk('sftpFiles')->size($fileName);

            $logo->value = $customerId;
            $logo->type = File::FILE_TYPE['customer_logo'];
            $logo->path = $path;
            $logo->disk_name = $fileName;
            $logo->file_name = $avatarFileName;
            $logo->file_size = $size;
            $logo->time = $time;
            $logo->save();

//            loxiloxi23432L
        }
    }
}