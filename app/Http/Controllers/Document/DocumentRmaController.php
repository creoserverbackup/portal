<?php

namespace App\Http\Controllers\Document;

use App\Http\Controllers\Controller;
use App\Http\Requests\RmaRequest;
use App\Services\Document\DocumentRmaService;
use Illuminate\Http\Request;

class DocumentRmaController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(DocumentRmaService $documentRmaService)
    {
        return response()->json($documentRmaService->get());
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(RmaRequest $request, DocumentRmaService $documentRmaService)
    {
        return $documentRmaService->save();
    }
}
