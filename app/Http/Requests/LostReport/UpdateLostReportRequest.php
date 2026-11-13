<?php

namespace App\Http\Requests\LostReport;

use App\Models\LostReport;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLostReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $lostReport = $this->route('lost_report');

        return $lostReport instanceof LostReport
            && ($this->user()?->can('update', $lostReport) ?? false);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $validCategories = array_keys(LostReport::availableCategories());
        $validStatuses = array_keys(LostReport::STATUSES);

        return [
            'item_name' => ['required', 'string', 'max:150'],
            'category' => ['required', 'string', 'max:80', Rule::in($validCategories)],
            'description' => ['required', 'string', 'min:10', 'max:2000'],
            'lost_date' => ['required', 'date', 'before_or_equal:today'],
            'location' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', Rule::in($validStatuses)],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
            'remove_photo' => ['nullable', 'boolean'],
        ];
    }
}
