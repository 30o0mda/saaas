<?php

namespace App\Http\Resources\StudentResult;

use Illuminate\Http\Resources\Json\JsonResource;


/**
 * @OA\Schema(
 *     schema="StudentResultResource",
 *     title="Student Result Resource",
 *     description="تفاصيل نتيجة الطالب في بنك الأسئلة",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=15, description="معرّف نتيجة الطالب"),
 *     @OA\Property(
 *         property="user",
 *         type="object",
 *         description="بيانات الطالب",
 *         @OA\Property(property="id", type="integer", example=7),
 *         @OA\Property(property="name", type="string", example="أحمد محمد")
 *     ),
 *     @OA\Property(property="organization_id", type="integer", example=3, description="معرّف المؤسسة التعليمية"),
 *     @OA\Property(
 *         property="question_bank",
 *         type="object",
 *         description="بيانات بنك الأسئلة المرتبط بالنتيجة",
 *         @OA\Property(property="id", type="integer", example=10),
 *         @OA\Property(property="name", type="string", example="اختبار نهاية الفصل الدراسي الأول")
 *     ),
 *     @OA\Property(property="is_finished", type="boolean", example=true, description="هل أنهى الطالب الاختبار؟"),
 *     @OA\Property(property="total_questions", type="integer", example=20, description="إجمالي عدد الأسئلة في بنك الأسئلة"),
 *     @OA\Property(property="answered_questions", type="integer", example=18, description="عدد الأسئلة التي أجاب عليها الطالب"),
 *     @OA\Property(property="correct_answers", type="integer", example=15, description="عدد الإجابات الصحيحة"),
 *     @OA\Property(
 *         property="answers",
 *         type="array",
 *         description="تفاصيل إجابات الطالب لكل سؤال",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="question_id", type="integer", example=5, description="معرّف السؤال"),
 *             @OA\Property(property="question_name", type="string", example="ما هي عاصمة فرنسا؟", description="نص السؤال"),
 *             @OA\Property(property="answer_id", type="integer", example=2, description="معرّف الإجابة المختارة"),
 *             @OA\Property(property="answer_text", type="string", example="باريس", description="نص الإجابة المختارة"),
 *             @OA\Property(property="is_correct", type="boolean", example=true, description="هل الإجابة صحيحة؟")
 *         )
 *     ),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-11-02 18:30:00", description="تاريخ إنشاء النتيجة")
 * )
 */
class StudentResultResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user' => [
                'id' => $this->user_id,
                'name' => $this->user->name ?? null,
            ],
            'organization_id' => $this->organization_id,
            'question_bank' => [
                'id' => $this->question_bank_id,
                'name' => $this->questionBank->name ?? null,
            ],
            'is_finished' => (bool) $this->is_finished,
            'total_questions' => $this->questionBank->questions->count() ?? 0,
            'answered_questions' => $this->studentResultAnswers->count() ?? 0,
            'correct_answers' => $this->studentResultAnswers
                ->where('is_correct', true)
                ->count(),
            'answers' => $this->studentResultAnswers->map(function ($answer) {
                return [
                    'question_id' => $answer->question_id,
                    'question_name' => $answer->question->question ?? null,
                    'answer_id' => $answer->answer_id,
                    'answer_text' => $answer->answer->answer ?? null,
                    'is_correct' => (bool) $answer->is_correct,
                ];
            }),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
