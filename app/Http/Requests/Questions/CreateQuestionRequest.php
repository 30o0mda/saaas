<?php

namespace App\Http\Requests\Questions;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations\MediaType;

class CreateQuestionRequest extends FormRequest
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
            'question_bank_id' => 'required|exists:question_banks,id',
            'question' => 'required|string',
            'media'=>'nullable|string' ?? null,
            'media_type'=>'nullable|integer' ?? null,
            'degree'=>'nullable|integer' ?? null,
            'question_type'=>'nullable|integer' ?? null,
            'difficulty'=>'nullable|integer' ?? null,
            'answers'=>'nullable|array' ?? null,
            'answers.*.answer'=>'nullable|string' ?? null,
            'answers.*.is_correct'=>'nullable|boolean' ?? null,
            'answers.*.media'=>'nullable|string' ?? null,
            'answers.*.media_type'=>'nullable|integer' ?? null
        ];
    }
}
