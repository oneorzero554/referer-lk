<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListCitiesRequest;
use App\Services\Api\ApiClient;
use App\Services\Api\Dtos\RequestsDtos\GetCitiesDto;
use Illuminate\Http\JsonResponse;

class CitiesController extends Controller
{
    private ApiClient $api;

    public function __construct(ApiClient $api)
    {
        $this->api = $api;
    }

    public function list(ListCitiesRequest $request): JsonResponse
    {
        return $this->successResponse($this->api->getCities(GetCitiesDto::from($request->validated())));
    }
}
