<?php

namespace App\Http\Requests;


class RegisterRequest extends FormRequestWithoutRedirect
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected string $message = 'Некорректные данные регистрации';

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'string',
                'email',
                'unique:App\Models\User,email'
            ],
            'password' => [
                'required',
                'string',
                'min:6',
                'regex:/(?=.*[0-9])(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z!@#$%^&*]{6,}/',
                'confirmed'
            ],
            'full_name' => [
                'required',
                'string',
                'max:255'
            ],
            'phone' => [
                'required',
                'string',
                'size:11',
                'unique:App\Models\User,phone'
            ],
            'dob' => [
                'required',
                'string',
                'date_format:Y-m-d'
            ],
        ];
    }
}
