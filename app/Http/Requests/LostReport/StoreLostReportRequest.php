<?php

namespace App\Http\Requests\LostReport;

use App\Models\LostReport;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLostReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('create', LostReport::class) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $validCategories = array_keys(LostReport::availableCategories());

        return [
            'item_name' => ['required', 'string', 'max:150'],
            'category' => ['required', 'string', 'max:80', Rule::in($validCategories)],
            'description' => ['required', 'string', 'min:10', 'max:2000'],
            'lost_date' => ['required', 'date', 'before_or_equal:today'],
            'location' => ['required', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
        ];
    }

    /**
     * Custom validation messages for clear student feedback.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'lost_date.before_or_equal' => 'The lost date cannot be in the future.',
            'photo.max' => 'Uploaded photo must not exceed 4 MB.',
        ];
    }
}
