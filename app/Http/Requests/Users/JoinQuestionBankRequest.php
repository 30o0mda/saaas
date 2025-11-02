<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class JoinQuestionBankRequest extends FormRequest
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
        'user_id' => 'required|exists:users,id',
        'question_bank_id' => 'nullable|exists:question_banks,id',
        'status' => 'nullable|string'
        ];
    }
}

