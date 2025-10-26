<?php

namespace App\Http\Requests\Courses;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseRequest extends FormRequest
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
            'course_id' => 'required|exists:courses,id',
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'stage_and_subject_id' => 'nullable|exists:stage_and_subjects,id',
            'price' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
            'organization_id' => 'nullable|exists:organizations,id',
            'organization_employees_id' => 'nullable|exists:organization_employees,id',
            'is_free' => 'nullable|boolean',
        ];
    }
}
