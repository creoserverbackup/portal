<?php

namespace App\Http\Controllers\Document;

use App\Http\Controllers\Controller;
use App\Services\Document\DocumentOfferCartService;

class DocumentController extends Controller
{

    public function store(DocumentOfferCartService $documentOfferCartService)
    {
        return $documentOfferCartService->get();
    }
}
