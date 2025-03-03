<?php

namespace App\Http\Controllers\Api;

use App\Actions\FilterAction;
use App\Http\Controllers\Controller;
use App\Services\Filter\FilterService;
use Illuminate\Http\Request;

class FilterController extends Controller
{
//    public function index(Request $request, FilterAction $filter)
//    {
//        $response = $filter->handle($request->all());
//
//        return response()->json($response);
//    }

    public function index(FilterService $filterService)
    {
        return response()->json($filterService->get());
    }
}
