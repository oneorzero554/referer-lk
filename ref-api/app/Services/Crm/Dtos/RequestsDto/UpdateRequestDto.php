<?php

namespace App\Services\Crm\Dtos\RequestsDto;

use Spatie\LaravelData\Data;

class UpdateRequestDto extends Data
{
    public function __construct(
        public ?string $ref_comment,
        public int $request_id
    )
    {
    }

}
