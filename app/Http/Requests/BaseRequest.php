<?php

namespace App\Http\Requests;

use App\Classes\Reply;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class BaseRequest extends FormRequest
{

    protected function formatErrors(Validator $validator): array
    {
        return Reply::formErrors($validator);
    }
}
