<?php

namespace App\Http\Requests;

class ListCitiesRequest extends FormRequestWithoutRedirect
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
            'site_id' => [
                'required',
                'integer',
            ],
            'provider_id' => [
                'integer'
            ],
            'query' => [
                'string',
                'nullable'
            ],
            'per_page' => [
                'integer'
            ],
            'page' => [
                'integer'
            ],
        ];
    }
}
