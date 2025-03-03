<?php

namespace App\Http\Controllers\Document;

use App\Http\Controllers\Controller;
use App\Services\Document\DocumentOfferService;

class DocumentOfferController extends Controller
{
    public function store(DocumentOfferService $documentOfferService)
    {
        return $documentOfferService->create();
    }
}
