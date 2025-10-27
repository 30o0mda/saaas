<?php

namespace App\Http\Requests\Sessions;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSessionRequest extends FormRequest
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
            'session_id' => 'required|exists:sessions,id',
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'type' => 'nullable|integer',
            'parent_id' => 'nullable|exists:sessions,id',
            'course_id' => 'nullable|exists:courses,id',

        ];
    }
}
