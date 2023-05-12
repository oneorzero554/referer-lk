<?php

namespace App\Services\Crm\Dtos;

use Spatie\LaravelData\Data;

class RequestDto extends Data
{
    const CRM_INTERNAL_STATUS_NEW = 1;
    const CRM_INTERNAL_STATUS_FAILED = 2;
    const CRM_INTERNAL_STATUS_OPENED = 3;
    const CRM_INTERNAL_STATUS_SUCCESSFUL = 4;
    const CRM_INTERNAL_STATUS_EXTERNAL_REQUEST = 5;
    const CRM_INTERNAL_STATUS_EXTERNAL_REQUEST_NOT_AVAILABLE = 6;
    const CRM_INTERNAL_STATUS_PAID = 7;
    const CRM_INTERNAL_STATUS_WAITING_FOR_CONNECTION = 8;
    const CRM_INTERNAL_STATUS_SECONDARY_OPENED = 9;
    const CRM_INTERNAL_STATUS_CALL_FAILED = 10;
    const CRM_INTERNAL_STATUS_REOPENED = 11;
    const CRM_INTERNAL_STATUS_PERSUASION = 12;
    const CRM_INTERNAL_STATUS_LEGAL = 13;
    const CRM_INTERNAL_STATUS_POSTAL_PROCESSING = 14;
    const CRM_INTERNAL_STATUS_ROLLBACK = 15;

    const REF_STATUS_IN_WORK = 1;
    const REF_STATUS_FAILED = 2;
    const REF_STATUS_SUCCESSFUL = 3;

    public function __construct(
        public int     $id,
        public string  $full_name,
        public string  $building_type,
        public int     $status_id,
        public string  $status_name,
        public string  $created_at,
        public ?string $ref_comment,
        public string  $selected_tariff,
        public ?string $provider_name,
        public string  $city
    )
    {
    }

    public static function fromCrm(array $data): self
    {
        $city = $data['city'];
        if ($data['request_city'] && $data['request_city']['subject']) {
            $city = "{$data['request_city']['name']} ({$data['request_city']['subject_name']})";
        }

        $provider_name = $data['source']['provider'] ? $data['source']['provider']['name'] : null;

        return new self(
            $data['id'],
            $data['full_name'],
            $data['building_type'],
            self::crmStatusToRefStatus()[$data['internal_status']],
            self::statusNames()[self::crmStatusToRefStatus()[$data['internal_status']]],
            $data['created_at'],
            $data['ref_comment'],
            $data['selected_tariff'],
            $provider_name,
            $city
        );
    }

    public static function statusNames(): array
    {
        return [
            self::REF_STATUS_FAILED => 'Отказ',
            self::REF_STATUS_IN_WORK => 'В работе',
            self::REF_STATUS_SUCCESSFUL => 'Успешно'
        ];
    }

    public static function refStatusToCrmStatus(): array
    {
        return [
            self::REF_STATUS_IN_WORK => [
                self::CRM_INTERNAL_STATUS_NEW,
                self::CRM_INTERNAL_STATUS_OPENED,
                self::CRM_INTERNAL_STATUS_EXTERNAL_REQUEST,
                self::CRM_INTERNAL_STATUS_EXTERNAL_REQUEST_NOT_AVAILABLE,
                self::CRM_INTERNAL_STATUS_PAID,
                self::CRM_INTERNAL_STATUS_WAITING_FOR_CONNECTION,
                self::CRM_INTERNAL_STATUS_SECONDARY_OPENED,
                self::CRM_INTERNAL_STATUS_CALL_FAILED,
                self::CRM_INTERNAL_STATUS_REOPENED,
                self::CRM_INTERNAL_STATUS_PERSUASION,
                self::CRM_INTERNAL_STATUS_LEGAL,
                self::CRM_INTERNAL_STATUS_POSTAL_PROCESSING,
                self::CRM_INTERNAL_STATUS_ROLLBACK
            ],
            self::REF_STATUS_FAILED => [self::CRM_INTERNAL_STATUS_FAILED],
            self::REF_STATUS_SUCCESSFUL => [self::CRM_INTERNAL_STATUS_SUCCESSFUL]
        ];
    }

    public static function crmStatusToRefStatus(): array
    {
        return [
            self::CRM_INTERNAL_STATUS_NEW => self::REF_STATUS_IN_WORK,
            self::CRM_INTERNAL_STATUS_FAILED => self::REF_STATUS_FAILED,
            self::CRM_INTERNAL_STATUS_OPENED => self::REF_STATUS_IN_WORK,
            self::CRM_INTERNAL_STATUS_SUCCESSFUL => self::REF_STATUS_SUCCESSFUL,
            self::CRM_INTERNAL_STATUS_EXTERNAL_REQUEST => self::REF_STATUS_IN_WORK,
            self::CRM_INTERNAL_STATUS_EXTERNAL_REQUEST_NOT_AVAILABLE => self::REF_STATUS_IN_WORK,
            self::CRM_INTERNAL_STATUS_PAID => self::REF_STATUS_IN_WORK,
            self::CRM_INTERNAL_STATUS_WAITING_FOR_CONNECTION => self::REF_STATUS_IN_WORK,
            self::CRM_INTERNAL_STATUS_SECONDARY_OPENED => self::REF_STATUS_IN_WORK,
            self::CRM_INTERNAL_STATUS_CALL_FAILED => self::REF_STATUS_IN_WORK,
            self::CRM_INTERNAL_STATUS_REOPENED => self::REF_STATUS_IN_WORK,
            self::CRM_INTERNAL_STATUS_PERSUASION => self::REF_STATUS_IN_WORK,
            self::CRM_INTERNAL_STATUS_LEGAL => self::REF_STATUS_IN_WORK,
            self::CRM_INTERNAL_STATUS_POSTAL_PROCESSING => self::REF_STATUS_IN_WORK,
            self::CRM_INTERNAL_STATUS_ROLLBACK => self::REF_STATUS_IN_WORK
        ];
    }
}
