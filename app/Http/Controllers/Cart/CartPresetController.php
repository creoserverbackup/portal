<?php

namespace App\Http\Controllers\Cart;

use App\Actions\CartPresetAction;
use App\Http\Controllers\Controller;


class CartPresetController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CartPresetAction $cartPresetAction)
    {
        return response()->json($cartPresetAction->handle());
    }
}
