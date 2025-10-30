<?php

namespace App\Http\Requests\Answers;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations\MediaType;

class CreateAnswerRequest extends FormRequest
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
            'question_id' => 'required|exists:questions,id',
            'answer' => 'required|string|max:255',
            'media' => 'nullable|string',
            'media_type' => 'nullable|integer',
            'is_correct' => 'nullable|boolean',
        ];
    }
}
