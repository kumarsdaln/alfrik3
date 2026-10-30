<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        $user = $this->route('user');

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
                Rule::unique('users', 'username')
                    ->ignore($user->id),
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($user->id),
            ],

            'password' => [
                'nullable',
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
