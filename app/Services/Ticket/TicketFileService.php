<?php

namespace App\Services\Ticket;

use App\Events\UserDataEvent;
use App\Models\File;
use App\Models\Message;
use App\Models\Settings;
use App\Services\Customer\CustomerUidService;
use App\Services\Event\EventAdminDataService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Exception;

class TicketFileService
{

    public CustomerUidService $customerUidService;
    public EventAdminDataService $eventAdminDataService;

    function __construct()
    {
        $this->customerUidService = new CustomerUidService();
        $this->eventAdminDataService = new EventAdminDataService();
    }

    /**
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function save()
    {
        $data = request()->all();
        $uploadFiles = request()->file('files');

        DB::beginTransaction();
        try {
            $uid = $this->customerUidService->checkApiUid();
            if (!empty($uploadFiles)) {
                $support = $this->customerUidService->support();


                foreach ($uploadFiles as $key => $value) {
                    $message = new Message();
                    $message->type = Message::MESSAGE_TYPE['ticket'];
                    $message->value = $data['ticketId'];
                    $message->uid = $uid;
                    $message->support = !empty($support);
                    $message->file = 1;
                    $message->message = '';
                    $message->time = time();
                    $message->save();

                    if (!in_array($value->getClientOriginalExtension(), Message::CHAT_TYPE_FILE)) {
                        throw new Exception('Unauthorized file format');
                    }

                    Storage::disk('sftpFiles')->put($value->hashName(), $value->get(), 'public');

                    $file = new File();
                    $file->type = File::FILE_TYPE['ticket'];
                    $file->value = $message->id;
                    $file->type_file = $value->getClientOriginalExtension();
                    $file->path = Storage::disk('sftpFiles')->url($value->hashName());
                    $file->disk_name = $value->hashName();
                    $file->file_name = $value->getClientOriginalName();
                    $file->file_size = Storage::disk('sftpFiles')->size($value->hashName());
                    $file->time = time();
                    $file->save();

                    event(new UserDataEvent(Settings::ADMIN_DATA_TYPE['ticket_update'], $message));

                    $this->eventAdminDataService->send(Settings::ADMIN_DATA_TYPE['ticket_update'], $message);
                }
                DB::commit();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 422);
        }
    }

}