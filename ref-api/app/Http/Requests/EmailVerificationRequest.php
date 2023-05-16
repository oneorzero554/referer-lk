<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest as originalEmailVerificationRequest;

class EmailVerificationRequest extends originalEmailVerificationRequest
{
    protected function prepareForValidation()
    {
        $this->setUserResolver(function () {
            return User::findOrFail($this->route('id'));
        });
    }
}
