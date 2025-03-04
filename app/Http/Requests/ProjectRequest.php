<?php

namespace App\Http\Requests;

use App\Models\Attribute;
use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'status' => 'required|in:0,1',
            'attributes' => ['required', 'array'],
            'attributes.*.attribute_id' => ['required', 'integer', 'exists:attributes,id'],
            'attributes.*.value' => ['required', 'string'],
            'attributes.*.start_date' => ['nullable', 'date_format:d-m-Y'],
            'attributes.*.end_date' => ['nullable', 'date_format:d-m-Y', 'after_or_equal:attributes.*.start_date'],
        ];
    }
}
