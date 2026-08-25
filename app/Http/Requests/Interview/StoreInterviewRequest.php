<?php

namespace App\Http\Requests\Interview;

use App\Enums\Interview\Status;
use App\Enums\Interview\Type;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreInterviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
         return [
            'title' => 'required|string|max:255',

            'slug' => [
                'nullable',
                'string',
                'max:255',
                'unique:interviews,slug'
            ],

            'description' => 'nullable|string',
            'duration' => 'nullable|integer|min:1',

            'interview_type' => [
                'required',
                Rule::enum(Type::class)
            ],
            'status' => [
                'nullable',
                Rule::enum(Status::class)
            ],
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'published_at' => ['required']
        ];
    }

     public function prepareForValidation()
    {
        if (!$this->slug && $this->title) {
            $this->merge([
                'slug' => Str::slug($this->title)
            ]);
        }
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Interview title is required.',
            'interview_type.required' => 'Select interview type.',
            'thumbnail.image' => 'Thumbnail must be an image.',
        ];
    }
}
