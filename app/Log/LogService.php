<?php

namespace App\Log;

use App\Models\Log;

class LogService
{

    public function save(
            $type,
            $value,
            $status = '',
    ) {

        $log = new Log();
        $log->type = $type;
        $log->value = $value;
        $log->status = $status;
        $log->save();

    }
}