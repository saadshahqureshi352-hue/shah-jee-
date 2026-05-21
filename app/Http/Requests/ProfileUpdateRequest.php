<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:25'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'owner_name' => ['nullable', 'string', 'max:255'],
            'home_address' => ['nullable', 'string', 'max:500'],
            'alternate_email' => [
                'nullable', 'string', 'lowercase', 'email', 'max:255',
                Rule::unique(User::class, 'alternate_email')->ignore($this->user()->id),
            ],
            'alternate_city' => ['nullable', 'string', 'max:100'],
        ];
    }
}