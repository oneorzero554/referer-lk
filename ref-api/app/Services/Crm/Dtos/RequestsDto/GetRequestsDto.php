<?php

namespace App\Services\Crm\Dtos\RequestsDto;

use App\Http\Requests\IndexRequestsRequest;
use App\Services\Crm\Dtos\RequestDto;
use Spatie\LaravelData\Data;

class GetRequestsDto extends Data
{
    public function __construct(
        public ?string $created_from,
        public ?string $created_to,
        public ?string $full_name,
        public ?string $ref_comment,
        public ?string $id,
        public ?string $city,
        public ?array $providers,
        public ?array $internal_statuses,
        public ?int $page
    )
    {
    }

    public static function fromRequest(IndexRequestsRequest $request)
    {
        $internal_statuses = [];

        if ($request->statuses) {
            foreach ($request->statuses as $item) {
                $internal_statuses = array_merge($internal_statuses, RequestDto::refStatusToCrmStatus()[$item]);
            }
        }

        return new self(
            $request->created_from,
            $request->created_to,
            $request->full_name,
            $request->ref_comment,
            $request->id,
            $request->city,
            $request->providers,
            $internal_statuses,
            $request->page
        );
    }
}
