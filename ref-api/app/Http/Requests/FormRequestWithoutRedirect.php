<?php

namespace App\Http\Requests;

use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class FormRequestWithoutRedirect extends FormRequest
{
    use ApiResponser;

    protected string $message = 'Некорректные данные';

//    protected $stopOnFirstFailure = true;

    protected function failedValidation(Validator $validator)
    {
        throw (new HttpResponseException($this->errorResponse($validator->errors(), $this->message, 422)));
    }
}
