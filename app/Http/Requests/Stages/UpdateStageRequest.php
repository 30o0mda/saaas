<?php

namespace App\Http\Requests\Stages;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStageRequest extends FormRequest
{
    protected $stage_id ;
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
            'stage_id' => 'required|exists:stages,id',
            'name' => 'required|string|max:255',
            'organization_id' => 'nullable|exists:organizations,id',
            'parent_id' => 'nullable|exists:stages,id',
            'education_type_id' => 'nullable|exists:education_types,id',
        ];
    }
}
