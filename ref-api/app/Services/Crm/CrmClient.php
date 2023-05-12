<?php

namespace App\Services\Crm;

use App\Services\Crm\Dtos\RequestDto;
use App\Services\Crm\Dtos\RequestsDto\CreateRequestDto;
use App\Services\Crm\Dtos\RequestsDto\GetRequestsDto;
use App\Services\Crm\Dtos\RequestsDto\UpdateRequestDto;
use App\Services\Crm\Dtos\RequestsIndexDto;
use App\Services\Crm\Dtos\SourceDto;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerInterface;

class CrmClient
{
    private string $baseUrl;
    private string $token;
    private ?string $timezone;

    private PendingRequest $request;

    private LoggerInterface $logger;

    public function __construct(string $baseUrl, string $token)
    {
        $this->baseUrl = $baseUrl;
        $this->token = $token;

        $this->request = Http::withHeaders([
            'X-Api-Key' => $this->token
        ])
            ->baseUrl($this->baseUrl);;
        $this->logger = Log::channel('crm-client');
    }

    private function logging(Response $response): void
    {
        $m = $response->toPsrResponse()->getReasonPhrase();

        $this->logger->critical($m, [
            'url' => (string)$response->effectiveUri(),
            'httpCode' => $response->status(),
            'reason' => $m,
            'body' => $response->body()
        ]);
    }

    public function getSources(string $referer_id): array
    {
        $response = $this->request->get('referer-source/index', [
            'referer_id' => $referer_id
        ]);

        if ($response->failed()) {
            return [];
        }

        $res = [];

        foreach ($response->json() as $item) {
            $res[] = SourceDto::fromCrm($item);
        }

        return $res;
    }

    public function getRequests(GetRequestsDto $data, string $user_id): RequestsIndexDto
    {
        $response = $this->request->get('referer-request', array_merge($data->toArray(), ['referer_id' => $user_id]));

        if ($response->failed()) {
            return (new RequestsIndexDto([], null));
        }

        $pagination = [
            'total' => $response->header('X-Pagination-Total-Count'),
            'current_page' => $response->header('X-Pagination-Current-Page'),
            'per_page' => $response->header('X-Pagination-Per-Page'),
            'last_page' => null,
            'from' => null,
            'to' => null
        ];

        return RequestsIndexDto::fromCrm($response->json(), $pagination);
    }

    /**
     * @throws RequestException
     */
    public function createRequest(CreateRequestDto $data, string $user_id): RequestDto
    {
        $response = $this->request->post("referer-request/create?referer_id={$user_id}", array_merge($data->toArray(), ['referer' => $user_id]));

        if ($response->failed()) {
            // TODO: тут лог
        }

        $response->throw();

        return RequestDto::fromCrm($response->json());
    }

    /**
     * @throws RequestException
     */
    public function updateRequest(UpdateRequestDto $data, string $user_id): RequestDto
    {
        $response = $this->request->post("referer-request/update?id={$data->request_id}&referer_id={$user_id}", $data->toArray());

        if ($response->failed()) {
            // TODO: тут лог
        }

        $response->throw();

        return RequestDto::fromCrm($response->json());
    }
}
