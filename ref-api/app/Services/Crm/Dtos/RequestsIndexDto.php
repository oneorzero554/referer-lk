<?php

namespace App\Services\Crm\Dtos;

use App\Services\Api\Dtos\PaginationDto;
use Spatie\LaravelData\Data;

class RequestsIndexDto extends Data
{
    public function __construct(
        public array          $requests,
        public ?PaginationDto $pagination
    )
    {
    }

    public static function fromCrm(array $data, ?array $pagination): self
    {
        $requests = [];

        foreach ($data as $item) {
            $requests[] = RequestDto::fromCrm($item);
        }

        return new self(
            $requests,
            $pagination ? PaginationDto::from($pagination) : null
        );
    }
}
