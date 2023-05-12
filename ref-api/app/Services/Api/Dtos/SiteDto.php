<?php

namespace App\Services\Api\Dtos;

use Spatie\LaravelData\Data;

class SiteDto extends Data
{
    public function __construct(
        public int    $id,
        public string $url,
        public array  $providers
    )
    {
    }

    public static function fromApi(array $data): self
    {
        $providers = [];

        if (count($data['providers'])) {
            foreach ($data['providers'] as $item) {
                $providers[] = ProviderDto::fromApi($item);
            }
        }

        return new self(
            $data['id'],
            $data['url'],
            $providers
        );
    }
}
