<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListCategoriesRequest;
use App\Services\Api\ApiClient;
use App\Services\Api\Dtos\RequestsDtos\GetCategoriesDto;
use Illuminate\Http\JsonResponse;

class CategoriesController extends Controller
{
    private ApiClient $api;

    public function __construct(ApiClient $api)
    {
        $this->api = $api;
    }

    public function list(ListCategoriesRequest $request): JsonResponse
    {
        return $this->successResponse($this->api->getCategories(GetCategoriesDto::from($request->validated())));
    }
}
