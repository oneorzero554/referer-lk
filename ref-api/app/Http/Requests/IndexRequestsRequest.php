<?php

namespace App\Http\Requests;

use App\Rules\IntegerArray;

class IndexRequestsRequest extends FormRequestWithoutRedirect
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'id' => [
                'integer',
                'nullable'
            ],
            'providers' => [
                'array',
                new IntegerArray()
            ],
            'full_name' => [
                'string',
                'max:255',
                'nullable'
            ],
            'city' => [
                'string',
                'max:255',
                'nullable'
            ],
            'ref_comment' => [
                'string',
                'max:1000',
                'nullable'
            ],
            'statuses' => [
                'array',
                new IntegerArray()
            ],
            'created_from' => [
                'date_format:Y-m-d H:i:s',
                'nullable'
            ],
            'created_to' => [
                'date_format:Y-m-d H:i:s',
                'nullable'
            ],
            'timezone' => [
                'string',
                'max:255',
                'nullable'
            ],
            'page' => [
                'integer'
            ],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $created_from = $this->created_from;
        $created_to = $this->created_to;

        if ($this->timezone) {
            if ($created_from) {
                $created_from = $this->convertTimeZone($created_from, $this->timezone);
            }

            if ($created_to) {
                $created_to = $this->convertTimeZone($created_to, $this->timezone);
            }
        }

        $this->merge([
            'created_from' => $created_from,
            'created_to' => $created_to
        ]);
    }

    private function convertTimeZone(string $date, string $timezone_from)
    {
        return (new \DateTime($date, new \DateTimeZone($timezone_from)))
            ->setTimezone(new \DateTimeZone('Asia/Vladivostok'))
            ->format('Y-m-d H:i:s');
    }

}
