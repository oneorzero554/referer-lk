<?php

namespace App\Services\Api;

use App\Services\Api\Dtos\CategoryDto;
use App\Services\Api\Dtos\CitiesListDto;
use App\Services\Api\Dtos\ProviderDto;
use App\Services\Api\Dtos\RequestsDtos\GetCategoriesDto;
use App\Services\Api\Dtos\RequestsDtos\GetCitiesDto;
use App\Services\Api\Dtos\RequestsDtos\GetProvidersDto;
use App\Services\Api\Dtos\SiteDto;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerInterface;

class ApiClient
{
    private string $baseUrl;

    private PendingRequest $request;

    private LoggerInterface $logger;

    public function __construct(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;

        $this->request = Http::baseUrl($this->baseUrl);
        $this->logger = Log::channel('api-client');
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

    public function getSites(): array
    {
        $response = $this->request->get('sites', [
            'is_ref' => true
        ]);

        if ($response->failed()) {
            return [];
        }

        $res = [];

        foreach ($response->json()['data'] as $item) {
            $res[] = SiteDto::fromApi($item);
        }

        return $res;
    }

    public function getCities(GetCitiesDto $data): CitiesListDto
    {
        $response = $this->request->get(
            "sites/$data->site_id/localities",
            [
                'hidden' => 'null',
                'sort_by' => 'name',
                'provider_id' => $data->provider_id,
                'name' => $data->query,
                'per_page' => $data->per_page,
                'page' => $data->page
            ]
        );

        if ($response->failed()) {
            return (new CitiesListDto([], null));
        }

        return CitiesListDto::fromApi($response->json());
    }

    public function getCategories(GetCategoriesDto $data): array
    {
        $response = $this->request
            ->get(
                "sites/$data->site_id/localities/$data->city_id/providers/$data->provider_id/categories",
                [
                    'hidden' => 'null',
                    'sort_by' => 'priority:desc',
                    'per_page' => '30',
                ]
            );

        if ($response->failed()) {
            return [];
        }

        $res = [];

        foreach ($response->json()['data'] as $item) {
            $res[] = CategoryDto::fromApi($item);
        }

        return $res;
    }

    public function getProviders(GetProvidersDto $data): array
    {
        $response = $this->request
            ->get(
                "sites/$data->site_id/localities/$data->city_id/providers",
                [
                    'hidden' => 'null',
                    'sort_by' => 'priority:desc',
                    'per_page' => '1000',
                ]
            );

        if ($response->failed()) {
            return [];
        }

        $res = [];

        foreach ($response->json()['data'] as $item) {
            $res[] = ProviderDto::fromApi($item);
        }

        return $res;
    }
}
