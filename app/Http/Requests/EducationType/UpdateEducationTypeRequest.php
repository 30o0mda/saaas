<?php

namespace App\Http\Requests\EducationType;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEducationTypeRequest extends FormRequest
{
    protected $education_type_id;
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
            'education_type_id' => 'required|exists:education_types,id',
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'organization_id' => 'nullable|exists:organizations,id'.$this->education_type_id,
            'is_active' => 'nullable|boolean'.$this->education_type_id,
        ];
    }
}
