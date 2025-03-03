<?php

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Services\Product\CatalogProductShareService;
use Illuminate\Http\Request;

class CatalogProductShareController extends Controller
{

    public function store(CatalogProductShareService $catalogProductShareService)
    {
        return response()->json($catalogProductShareService->get());
    }
}
