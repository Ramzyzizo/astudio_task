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
            'attributes.*' => 'required|string',
            'attributes' => ['required', 'array', function ($attribute, $value, $fail) {
                $existingAttributes = Attribute::whereIn('id', array_keys($value))->pluck('id')->toArray();
                foreach (array_keys($value) as $attributeId) {
                    if (!in_array($attributeId, $existingAttributes)) {
                        $fail("The attribute ID {$attributeId} does not exist for {$attribute}.");
                    }
                }
            }]
        ];
    }
}
