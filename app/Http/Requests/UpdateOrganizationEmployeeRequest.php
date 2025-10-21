<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrganizationEmployeeRequest extends FormRequest
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
            'organization_employee_id' => 'required|exists:organization_employees,id',
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:organization_employees,email,' . $this->organization_employee_id,
            'phone' => 'nullable|string|unique:organization_employees,phone,' . $this->organization_employee_id,
        ];
    }
}

