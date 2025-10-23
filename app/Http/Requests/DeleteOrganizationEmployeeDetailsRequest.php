<?php

namespace App\Http\Requests;

use App\Models\OrganizationEmployee;
use Illuminate\Foundation\Http\FormRequest;

class DeleteOrganizationEmployeeDetailsRequest extends FormRequest
{
    protected $organization_employee_id;
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
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $organization_employee = OrganizationEmployee::find($this->organization_employee_id);
            if ($organization_employee && $organization_employee->is_master == 1) {
                $validator->errors()->add('organization_employee_id', 'Organization employee is master');
            }
        });
    }
}
