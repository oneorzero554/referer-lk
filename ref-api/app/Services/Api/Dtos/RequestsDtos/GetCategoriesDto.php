<?php

namespace App\Services\Api\Dtos\RequestsDtos;

use Spatie\LaravelData\Data;

class GetCategoriesDto extends Data
{
    public function __construct(
        public int $site_id,
        public int $provider_id,
        public int $city_id,
        public int $page = 1,
        public int $per_page = 15,
    )
    {
    }
}
