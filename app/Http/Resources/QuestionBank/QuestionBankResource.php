<?php

namespace App\Http\Resources\QuestionBank;

use App\Http\Resources\Questions\QuestionsResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * @OA\Schema(
 *     schema="QuestionBankResource",
 *     title="Question Bank Resource",
 *     description="نموذج يمثل بنك الأسئلة ومحتواه من الأسئلة",
 *     @OA\Property(property="id", type="integer", example=1, description="معرّف بنك الأسئلة"),
 *     @OA\Property(property="name", type="string", example="بنك أسئلة الرياضيات - الصف الثالث", description="اسم بنك الأسئلة"),
 *     @OA\Property(property="description", type="string", nullable=true, example="يحتوي على مجموعة من أسئلة الرياضيات للصف الثالث.", description="وصف مختصر لبنك الأسئلة"),
 *     @OA\Property(property="organization_id", type="integer", example=2, description="معرّف المؤسسة التعليمية المالكة لبنك الأسئلة"),
 *     @OA\Property(property="stage_and_subject_id", type="integer", example=10, description="معرّف المرحلة والمادة المرتبطة ببنك الأسئلة"),
 *     @OA\Property(property="image", type="string", nullable=true, example="https://example.com/storage/images/question_bank.png", description="رابط صورة بنك الأسئلة"),
 *     @OA\Property(property="is_active", type="boolean", example=true, description="هل بنك الأسئلة مفعّل أم لا"),
 *     @OA\Property(property="order", type="integer", example=1, description="ترتيب العرض لبنك الأسئلة"),
 *     @OA\Property(property="price", type="number", format="float", example=49.99, description="سعر الاشتراك في بنك الأسئلة (إن وُجد)"),
 *     @OA\Property(
 *         property="questions",
 *         type="array",
 *         description="الأسئلة التابعة لبنك الأسئلة",
 *         @OA\Items(ref="#/components/schemas/QuestionsResource")
 *     )
 * )
 */
class QuestionBankResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id ?? null,
            'name' => $this->name ?? null,
            'description' => $this->description ?? null,
            'organization_id' => $this->organization_id ?? null,
            'stage_and_subject_id' => $this->stage_and_subject_id ?? null,
            'image' => $this->image ? url('storage/'.$this->image): null,
            'is_active' => (int)$this->is_active ?? false,
            'order' => $this->order ?? null,
            'price' => $this->price ?? null,
            'questions'=>QuestionsResource::collection($this->questions)
        ];
    }
}
