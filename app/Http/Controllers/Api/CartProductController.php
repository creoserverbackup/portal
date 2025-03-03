<?php

namespace App\Http\Controllers\Api;

use App\Actions\CartPresetAction;
use App\Http\Controllers\Controller;
use App\Services\Cart\CartProductService;


class CartProductController extends Controller
{
    public function index(CartPresetAction $cartPresetAction)
    {
        return response()->json($cartPresetAction->handle());
    }

    public function store(CartProductService $cartProductService)
    {
        $cartProductService->add();
    }
}
