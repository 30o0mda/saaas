<?php

namespace App\Http\Resources\Questions;

use App\Http\Enum\QuestionsEnum\DifficultyQuestionEnum;
use App\Http\Enum\QuestionsEnum\MediaTypeEnum;
use App\Http\Enum\QuestionsEnum\QuestionsTypeEnum;
use App\Http\Resources\Answers\AnswersResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations\MediaType;
use SebastianBergmann\Diff\Diff;



/**
 * @OA\Schema(
 *     schema="QuestionsResource",
 *     title="Questions Resource",
 *     description="نموذج يمثل سؤال داخل بنك الأسئلة مع الإجابات الخاصة به",
 *     @OA\Property(property="id", type="integer", example=5, description="معرّف السؤال"),
 *     @OA\Property(property="question", type="string", example="ما هي عاصمة فرنسا؟", description="نص السؤال"),
 *     @OA\Property(property="degree", type="integer", example=2, description="درجة السؤال"),
 *     @OA\Property(property="is_active", type="boolean", example=true, description="هل السؤال مفعل أم لا"),
 *     @OA\Property(property="question_bank_id", type="integer", example=3, description="معرّف بنك الأسئلة المرتبط بهذا السؤال"),
 *     @OA\Property(
 *         property="question_type",
 *         type="string",
 *         enum={"choice", "true_false", "essay"},
 *         example="choice",
 *         description="نوع السؤال (اختياري، صح أو خطأ، مقالي)"
 *     ),
 *     @OA\Property(
 *         property="difficulty",
 *         type="string",
 *         enum={"easy", "medium", "hard"},
 *         example="medium",
 *         description="مستوى صعوبة السؤال"
 *     ),
 *     @OA\Property(property="media", type="string", nullable=true, example="https://example.com/images/question1.png", description="رابط الوسائط (صورة / صوت / فيديو) إن وُجد"),
 *     @OA\Property(
 *         property="media_type",
 *         type="string",
 *         enum={"image", "audio", "video", "text"},
 *         example="image",
 *         description="نوع الوسائط المرتبطة بالسؤال"
 *     ),
 *     @OA\Property(
 *         property="answers",
 *         type="array",
 *         description="قائمة بالإجابات المحتملة لهذا السؤال",
 *         @OA\Items(ref="#/components/schemas/AnswerResource")
 *     )
 * )
 */
class QuestionsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id ?? null,
            'question' => $this->question ?? null,
            'degree' => $this->degree ?? null,
            'is_active' => $this->is_active ?? null,
            'question_bank_id' => $this->question_bank_id ?? null,
            'question_type' => $this->question_type->value,
            'difficulty' => $this->difficulty->value,
            'media' => $this->media ?? null,
            'media_type' => $this->media_type->value,
            'answers' => AnswersResource::collection($this->answers)
        ];
    }
}
