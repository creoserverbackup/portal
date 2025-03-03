<?php

namespace App\Services\Chat;

use App\Models\File;
use App\Models\Message;
use App\Models\Settings;
use App\Services\Customer\CustomerUidService;
use App\Services\Event\EventAdminDataService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ChatMessagesFilesService
{
    public CustomerUidService $customerUidService;
    public EventAdminDataService $eventAdminDataService;

    function __construct()
    {
        $this->customerUidService = new CustomerUidService();
        $this->eventAdminDataService = new EventAdminDataService();
    }

    public function save($data)
    {
        $uploadFiles = request()->file('files');

        DB::beginTransaction();
        try {
            $uid = $this->customerUidService->checkApiUid();
            if (!empty($uploadFiles)) {
                $support = $this->customerUidService->support();
                $message = '';
                foreach ($uploadFiles as $key => $value) {
                    $message = new Message();
                    $message->type = Message::MESSAGE_TYPE['chat'];
                    $message->value = $data['chatId'];
                    $message->uid = $uid;
                    $message->support = !empty($support);
                    $message->file = 1;
                    $message->message = '';
                    $message->time = time();
                    $message->save();

                    if (!in_array($value->getClientOriginalExtension(), Message::CHAT_TYPE_FILE)) {
                        throw new Exception('Unauthorized file format');
                    }

                    if ($value->getSize() >  Message::MESSAGE_FILE_MEMORY) {
                        throw new Exception('Maximum dimension 200m exceeded');
                    }

                    Storage::disk('sftpFiles')->put($value->hashName(), $value->get(), 'public');

                    $file = new File();
                    $file->type = File::FILE_TYPE['chat'];
                    $file->value = $message->id;
                    $file->type_file = $value->getClientOriginalExtension();
                    $file->path = Storage::disk('sftpFiles')->url($value->hashName());
                    $file->disk_name = $value->hashName();
                    $file->file_name = $value->getClientOriginalName();
                    $file->file_size = Storage::disk('sftpFiles')->size($value->hashName());
                    $file->time = time();
                    $file->save();
                }
                DB::commit();
                $this->eventAdminDataService->send(Settings::ADMIN_DATA_TYPE['chat_message_new'], $message);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => [$e->getMessage()]], 402);
        }
    }
}
