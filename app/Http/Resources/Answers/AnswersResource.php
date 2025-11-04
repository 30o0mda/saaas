<?php

namespace App\Http\Resources\Answers;

use App\Http\Enum\QuestionsEnum\DifficultyQuestionEnum;
use App\Http\Enum\QuestionsEnum\MediaTypeEnum;
use App\Http\Enum\QuestionsEnum\QuestionsTypeEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations\MediaType;
use SebastianBergmann\Diff\Diff;


/**
 * @OA\Schema(
 *     schema="AnswerResource",
 *     title="Answer Resource",
 *     description="نموذج يمثل إجابة سؤال في بنك الأسئلة",
 *     @OA\Property(property="id", type="integer", example=1, description="معرّف الإجابة"),
 *     @OA\Property(property="answer", type="string", example="القاهرة", description="نص الإجابة"),
 *     @OA\Property(property="is_correct", type="boolean", example=true, description="هل الإجابة صحيحة؟"),
 *     @OA\Property(property="media", type="string", nullable=true, example="https://example.com/storage/answers/1.png", description="رابط الوسائط (صورة أو صوت) المرتبطة بالإجابة"),
 *     @OA\Property(property="media_type", type="string", example="image", description="نوع الوسائط (image, audio, video)")
 * )
 * 
 * )
 */
class AnswersResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id ?? null,
            'answer' => $this->answer ?? null,
            'is_correct' => $this->is_correct ?? null,
            'media' => $this->media ?? null,
            'media_type' => $this->media_type
        ];
    }
}
