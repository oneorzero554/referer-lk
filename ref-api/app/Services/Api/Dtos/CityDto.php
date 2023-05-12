<?php

namespace App\Services\Api\Dtos;

use Spatie\LaravelData\Data;

class CityDto extends Data
{
    public function __construct(
        public int    $id,
        public string $name,
        public string $region_name,
        public string $url
    )
    {
    }

    public static function fromApi(array $data): self
    {
        return new self(
            $data['id'],
            $data['name'],
            $data['region']['name'],
            $data['url']
        );
    }
}
