<?php

namespace App\Service\Subject;

use App\Helpers\ApiResponseHelper;
use App\Http\Resources\Subjects\SubjectResource;
use App\Models\Subject;

class SubjectService
{
    public function __construct()
    {
    }

        public function createSubject( $data) {
        $subject = Subject::create([
            'name' => $data['name'],
            'description' => $data['description'],
        ]);
        return ApiResponseHelper::response(true, 'تم إنشاء الموضوع بنجاح', [
            'subject' => new SubjectResource($subject),
        ]);
    }


        public function updateSubject( $data) {
        $subject = Subject::find($data['subject_id']);
        $subject->update([
            'name' => $data['name'] ?? $subject->name,
            'description' => $data['description'] ?? $subject->description,
        ]);
        return ApiResponseHelper::response(true, 'تم تحديث الموضوع بنجاح', [
            'subject' => new SubjectResource($subject),
        ]);
    }

        public function fetchSubject( $data) {
        $subject = Subject::find($data['subject_id']);
        return ApiResponseHelper::response(true, 'تم جلب الموضوع بنجاح', [
            'subject' => new SubjectResource($subject),
        ]);
    }

        public function deleteSubject( $data) {
        $subject = Subject::find($data['subject_id']);
        $subject->delete();
        return ApiResponseHelper::response(true, 'تم حذف الموضوع بنجاح');
    }
}

