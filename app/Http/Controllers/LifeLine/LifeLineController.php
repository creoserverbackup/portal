<?php

namespace App\Http\Controllers\LifeLine;

use App\Http\Controllers\Controller;
use App\Services\Lifeline\LifelineService;

class LifeLineController extends Controller
{

    public function index(LifelineService $lifelineService)
    {
        return response()->json($lifelineService->index());
    }

    public function show(string $type, string $value, LifelineService $lifelineService)
    {
        return $lifelineService->getType($type, $value);
    }

    public function destroy($id, LifelineService $lifelineService)
    {
        return $lifelineService->delete($id);
    }
}
