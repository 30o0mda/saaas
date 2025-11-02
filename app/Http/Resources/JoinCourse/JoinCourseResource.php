<?php

namespace App\Http\Resources\JoinCourse;

use App\Http\Enum\JoinCourseEnum;
use App\Http\Resources\Sessions\SessionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * @OA\Schema(
 *     schema="JoinCourseResource",
 *     title="Join Course Resource",
 *     description="نموذج يمثل بيانات اشتراك الطالب في الكورس",
 *     @OA\Property(property="id", type="integer", example=10, description="معرّف السجل"),
 *     @OA\Property(property="user_id", type="integer", example=5, description="معرّف المستخدم الذي انضم للكورس"),
 *     @OA\Property(property="course_id", type="integer", example=3, description="معرّف الكورس"),
 *     @OA\Property(property="organization_id", type="integer", example=1, description="معرّف المؤسسة التعليمية"),
 *     @OA\Property(property="account_number", type="string", nullable=true, example="123456789", description="رقم الحساب المستخدم في الدفع (إن وُجد)"),
 *     @OA\Property(property="image", type="string", nullable=true, example="https://example.com/storage/payment_receipt.png", description="صورة إيصال الدفع"),
 *     @OA\Property(property="status", type="string", example="pending", description="حالة الانضمام للكورس (مثل: pending, approved, rejected)")
 * )
 */
class JoinCourseResource extends JsonResource
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
            'user_id' => $this->user_id ?? null,
            'course_id' => $this->course_id ?? null,
            'organization_id' => $this->organization_id ?? null,
            'account_number' => $this->account_number ?? null,
            'image' => $this->image ? url('storage/'.$this->image): null,
            'status' => $this->status ?? null,
        ];}
}
