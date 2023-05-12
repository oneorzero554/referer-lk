<?php

namespace App\Services\Api\Dtos;

use Spatie\LaravelData\Data;

class CitiesListDto extends Data
{
    public function __construct(
        public array         $data,
        public ?PaginationDto $meta
    )
    {
    }

    public static function fromApi(array $data): self
    {
        $cities = [];

        foreach ($data['data'] as $item) {
            $cities[] = CityDto::fromApi($item);
        }

        return new self(
            $cities,
            isset($data['meta']) ? PaginationDto::from($data['meta']) : null
        );
    }
}
