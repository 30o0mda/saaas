<?php

namespace App\Http\Resources\Sessions;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;



/**
 * @OA\Schema(
 *     schema="SessionResource",
 *     title="Session Resource",
 *     description="تفاصيل الجلسة (Session) ضمن الكورس، وقد تحتوي على جلسات فرعية (Children)",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=5, description="معرّف الجلسة"),
 *     @OA\Property(property="name", type="string", example="مقدمة في البرمجة", description="اسم الجلسة"),
 *     @OA\Property(property="description", type="string", example="جلسة تشرح أساسيات البرمجة بلغة PHP", description="وصف الجلسة"),
 *     @OA\Property(property="course_id", type="integer", example=1, description="معرّف الكورس الذي تنتمي إليه الجلسة"),
 *     @OA\Property(property="parent_id", type="integer", nullable=true, example=null, description="معرّف الجلسة الأب إن كانت جلسة فرعية"),
 *     @OA\Property(property="link", type="string", nullable=true, example="https://example.com/session/intro", description="رابط الجلسة (في حال كانت فيديو أو بث مباشر)"),
 *     @OA\Property(property="file", type="string", nullable=true, example="https://example.com/storage/files/lecture1.pdf", description="ملف مرفق مع الجلسة إن وُجد"),
 *     @OA\Property(property="order", type="integer", example=1, description="ترتيب الجلسة داخل الكورس"),
 *     @OA\Property(property="is_active", type="boolean", example=true, description="حالة تفعيل الجلسة"),
 *     @OA\Property(property="type", type="string", example="فيديو", description="نوع الجلسة (فيديو - PDF - واجب عملي - اختبار)"),
 *     @OA\Property(
 *         property="children",
 *         type="array",
 *         description="الجلسات الفرعية المرتبطة بهذه الجلسة (إن وُجدت)",
 *         @OA\Items(ref="#/components/schemas/SessionResource")
 *     )
 * )
 */

class SessionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id ?? null,
            'name' => $this->name ?? null,
            'description' => $this->description ?? null,
            'course_id' => $this->course_id ?? null,
            'parent_id' => $this->parent_id ? (int)$this->parent_id : null,
            'link' => $this->link ?? null,
            'file' => $this->file ?? null,
            'order' => $this->order ?? 1,
            'is_active' => $this->is_active ?? 1,
            'type' => $this->type->label() ?? null,
            'children' => SessionResource::collection($this?->children) ?? [],
        ];
    }
}
