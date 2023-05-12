<?php

namespace App\Services\Api\Dtos;

use Spatie\LaravelData\Data;

class PaginationDto extends Data
{
    public function __construct(
        public int $current_page,
        public ?int $from,
        public ?int $last_page,
        public int $per_page,
        public ?int $to,
        public int $total
    )
    {
    }
}
