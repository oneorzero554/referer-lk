<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListProvidersRequest;
use App\Services\Api\ApiClient;
use App\Services\Api\Dtos\RequestsDtos\GetProvidersDto;
use Illuminate\Http\JsonResponse;

class ProvidersController extends Controller
{
    private ApiClient $api;

    public function __construct(ApiClient $api)
    {
        $this->api = $api;
    }

    public function list(ListProvidersRequest $request): JsonResponse
    {
        return $this->successResponse($this->api->getProviders(GetProvidersDto::from($request->validated())));
    }
}
