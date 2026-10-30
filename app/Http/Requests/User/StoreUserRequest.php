<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'username' => [
                'nullable',
                'string',
                'max:50',
                'unique:users,username',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],

            'headline' => [
                'nullable',
                'string',
                'max:255',
            ],

            'country_id' => [
                'nullable',
                'integer',
                'exists:countries,id',
            ],

            'position_id' => [
                'nullable',
                'integer',
                'exists:positions,id',
            ],

            'is_active' => [
                'required',
                'boolean',
            ],

            'roles' => [
                'nullable',
                'array',
            ],

            'roles.*' => [
                'integer',
                'exists:roles,id',
            ],
        ];
    }
}
