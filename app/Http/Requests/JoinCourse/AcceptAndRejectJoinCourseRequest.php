<?php

namespace App\Http\Requests\JoinCourse;

use Illuminate\Foundation\Http\FormRequest;

class AcceptAndRejectJoinCourseRequest extends FormRequest
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
            'join_course_request_id' => 'required|exists:join_course_requests,id',
        ];
    }
}
