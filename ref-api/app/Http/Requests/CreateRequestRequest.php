<?php

namespace App\Http\Requests;

class CreateRequestRequest extends FormRequestWithoutRedirect
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
            'source' => [
                'required',
                'max:255'
            ],
            'phone' => [
                'required',
                'size:11'
            ],
            'full_name' => [
                'required',
                'max:255',
            ],
            'building_type' => [
                'required',
                'in:Квартира,Частный дом,Офис'
            ],
            'city' => [
                'required',
                'max:255'
            ],
            'address' => [
                'max:255'
            ],
            'ref_comment' => [
                'max:1000'
            ],
        ];
    }
}
