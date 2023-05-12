<?php

namespace App\Services\Api\Dtos\RequestsDtos;

use Spatie\LaravelData\Data;

class GetProvidersDto extends Data
{
    public function __construct(
        public int $site_id,
        public int $city_id,
    )
    {
    }
}
