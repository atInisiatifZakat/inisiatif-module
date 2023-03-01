<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class AuthTokenRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required',
        ];
    }
}
