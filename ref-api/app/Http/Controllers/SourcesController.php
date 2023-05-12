<?php

namespace App\Http\Controllers;

use App\Services\Crm\CrmClient;
use Illuminate\Http\JsonResponse;

class SourcesController extends Controller
{
    private CrmClient $crm;

    public function __construct(CrmClient $crm)
    {
        $this->crm = $crm;
    }

    public function list(): JsonResponse
    {
        return $this->successResponse($this->crm->getSources(auth()->id()));
    }
}
