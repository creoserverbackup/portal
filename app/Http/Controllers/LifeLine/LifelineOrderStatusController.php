<?php

namespace App\Http\Controllers\LifeLine;

use App\Http\Controllers\Controller;
use App\Models\TaskOrder;

class LifelineOrderStatusController extends Controller
{

    public function index()
    {

        $statuses = TaskOrder::STATUS_NAME;
        $arr = [];

        foreach ($statuses as $key => $status) {

            if ($key == 9 || $key == 3 || $key == 100) {
                continue;
            }

            array_push(
                    $arr,
                    [
                            'id' => $key,
                            'name' => $status,
                            'value' => $key
                    ]
            );
        }
        return $arr;
    }
}
