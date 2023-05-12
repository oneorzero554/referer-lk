<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRequestRequest;
use App\Http\Requests\IndexRequestsRequest;
use App\Http\Requests\UpdateRequestRequest;
use App\Services\Crm\CrmClient;
use App\Services\Crm\Dtos\RequestsDto\CreateRequestDto;
use App\Services\Crm\Dtos\RequestsDto\GetRequestsDto;
use App\Services\Crm\Dtos\RequestsDto\UpdateRequestDto;
use Illuminate\Http\Client\RequestException;

class RequestsController extends Controller
{
    private CrmClient $crm;

    public function __construct(CrmClient $crm)
    {
        $this->crm = $crm;
    }

    public function index(IndexRequestsRequest $request)
    {
        return $this->crm->getRequests(GetRequestsDto::fromRequest($request), auth()->id());
    }

    /**
     * @throws RequestException
     */
    public function create(CreateRequestRequest $request)
    {
        return $this->crm->createRequest(CreateRequestDto::from($request->validated()), auth()->id());
    }

    /**
     * @throws RequestException
     */
    public function update(UpdateRequestRequest $request)
    {
        return $this->crm->updateRequest(UpdateRequestDto::from($request->validated()), auth()->id());
    }
}
