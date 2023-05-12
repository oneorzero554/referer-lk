<?php

namespace App\Services\Crm\Dtos\RequestsDto;

use Spatie\LaravelData\Data;

class CreateRequestDto extends Data
{
    public function __construct(
        public string $source,
        public string $full_name,
        public string $phone,
        public string $city,
        public string $building_type,
        public ?string $address,
        public ?string $ref_comment
    )
    {
    }

}
