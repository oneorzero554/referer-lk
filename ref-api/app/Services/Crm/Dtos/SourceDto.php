<?php

namespace App\Services\Crm\Dtos;

use Spatie\LaravelData\Data;

class SourceDto extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $provider_name,
        public int $provider_id
    )
    {
    }

    public static function fromCrm(array $data): self
    {
        $provider_name = $data['name'];
        $provider_id = null;

        if ($data['provider']) {
            $provider_name = $data['provider']['name'];
            $provider_id = $data['provider']['id'];
        }
        return new self(
            $data['id'],
            $data['name'],
            $provider_name,
            $provider_id
        );
    }
}
