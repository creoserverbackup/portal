<?php

namespace App\Http\Controllers\Statistic;

use App\Http\Controllers\Controller;
use App\Services\Statistic\StatisticVisitsService;

class StatisticVisitsController extends Controller
{

    public function store(StatisticVisitsService $statisticVisitsService)
    {
        return response()->json($statisticVisitsService->save());
    }

    public function destroy($uid, StatisticVisitsService $statisticVisitsService)
    {
        return response()->json($statisticVisitsService->delete());
    }
}
