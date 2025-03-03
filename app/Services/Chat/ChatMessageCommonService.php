<?php

namespace App\Services\Chat;

use App\Models\Message;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ChatMessageCommonService
{

    public function get($value, $typeMessage, $typeFile, $support)
    {
        $query = DB::table('message', 'm')
                ->leftJoin('file as f',function ($join) use ($typeFile) {
                    $join->on('f.value', '=', 'm.id')->where('f.type', '=', $typeFile);
                })
                ->leftJoin('users as u', 'u.id', '=', 'm.uid')
                ->selectRaw('u.username')
                ->selectRaw('m.read')
                ->selectRaw('m.uid')
                ->selectRaw('m.time')
                ->selectRaw('m.support')
                ->selectRaw('m.message')
                ->selectRaw('m.created_at')
                ->selectRaw('f.disk_name')
                ->selectRaw('f.file_name')
                ->selectRaw('f.type_file')
                ->where('m.value', $value)
                ->where('m.type', $typeMessage)
                ->oldest('m.time');

        if (empty($support)) {
            $query->whereNotIn('m.status', [Message::STATUS['hide']]);
        }

        $messages = $query->get();

        if (!empty($messages)) {
            $messages = $this->rebuildingMessages($messages);
        }

        return $messages;
    }


    public function rebuildingMessages($messages)
    {
        $result = [];
        $date = '';
        foreach ($messages as $message) {
            $message->image = in_array($message->type_file, ['png', 'jpeg', 'jpg']);
            $message->created_at = date("D d M Y", strtotime($message->created_at));
            if (empty($date) || $date != $message->created_at) {
                $date = $message->created_at;
                $message->needDate = true;
            } else {
                $message->needDate = false;
            }

            if (!empty($message->disk_name)) {
                $message->message = $message->file_name;
                $message->url = Storage::disk('sftpFiles')->url($message->disk_name);
            }

            $result[] = $message;
        }
        return $messages;
    }
}