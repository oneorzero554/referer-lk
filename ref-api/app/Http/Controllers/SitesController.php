<?php

namespace App\Http\Controllers;

use App\Services\Api\ApiClient;
use Illuminate\Http\JsonResponse;

class SitesController extends Controller
{
    private ApiClient $api;

    public function __construct(ApiClient $api)
    {
        $this->api = $api;
    }

    public function list(): JsonResponse
    {
        return $this->successResponse($this->api->getSites());
    }
}
