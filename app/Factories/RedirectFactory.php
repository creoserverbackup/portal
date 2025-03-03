<?php

namespace App\Factories;

use App\Dto\RedirectDto;
use App\Models\Redirect;

class RedirectFactory
{
    public function createFromArray($data): RedirectDto
    {
        $redirect         = new RedirectDto();
        $redirect->status = $data['status'];
        $redirect->path   = $data['path'];

        return $redirect;
    }

    public function createFromRedirect(Redirect $redirect): RedirectDto
    {
        $redirectDto         = new RedirectDto();
        $redirectDto->status = $redirect->redirect_status;
        $redirectDto->path   = $redirect->to;

        return $redirectDto;
    }
}
