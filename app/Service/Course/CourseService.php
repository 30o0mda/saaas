<?php

namespace App\Service\Course;

use App\Helpers\ApiResponseHelper;
use App\Http\Resources\Courses\CourseResource;
use App\Models\course;

class CourseService
{
    public function __construct()
    {
    }

        public function createCourse( $data) {
        $order = course::where('organization_id', auth()->user()->organization_id)->max('order') + 1;
        $course = course::create([
            'name' => $data['name'],
            'description' => $data['description'],
            'organization_id' => auth()->user()->organization_id,
            // 'is_active' => $data['is_active'],
            'order' => $order,
            'stage_and_subject_id' => $data['stage_and_subject_id'],
            'price' => $data['price'],
            'is_free' => $data['is_free'],
        ]);
        return ApiResponseHelper::response(true, 'تم إنشاء الكورس بنجاح', [
            'course' => new CourseResource($course),
        ]);
    }

       public function updateCourse( $data) {
        $course = course::find($data['course_id']);
        $course->update([
            'name' => $data['name'] ?? $course->name,
            'description' => $data['description'] ?? $course->description,
            'is_active' => $data['is_active'] ?? $course->is_active,
            'order' => $data['order'] ?? $course->order,
            'stage_and_subject_id' => $data['stage_and_subject_id'] ?? $course->stage_and_subject_id,
            'price' => $data['price'] ?? $course->price,
            'is_free' => $data['is_free'] ?? $course->is_free,
        ]);
        return ApiResponseHelper::response(true, 'تم تحديث الكورس بنجاح', [
            'course' => new CourseResource($course),
        ]);
    }

        public function fetchCourseDetails( $data) {
        $course = course::find($data['course_id']);
        return ApiResponseHelper::response(true, 'تم جلب الكورس بنجاح', [
            'course' => new CourseResource($course),
        ]);
    }

            public function deleteCourse( $data) {
        $course = course::find($data['course_id']);
        $course->delete();
        return ApiResponseHelper::response(true, 'تم حذف الكورس بنجاح', [
            'course' => new CourseResource($course),
        ]);
    }

        public function isActive( $data) {
        $course = course::find($data['course_id']);
        $is_active = $course->is_active;
        $course->update([
            'is_active' => !$is_active
        ]);
        return ApiResponseHelper::response(true, 'تم تغيير حالة الكورس بنجاح', [
            'course' => new CourseResource($course),
        ]);
    }


        public function orderCourse( $data) {
        $course = course::find($data['course_id']);
        $course->update([
            'order' => $data['order']
        ]);
        Course::where('organization_id', auth()->user()->organization_id)->where('order', '>=', $data['order'])->increment('order');
        return ApiResponseHelper::response(true, 'تم تغيير ترتيب الكورس بنجاح', [
            'course' => new CourseResource($course),
        ]);
    }


        public function fetchAllCourses( $data) {
        $query = Course::where('organization_id', auth()->user()->organization_id);

        if(isset($data['word'])) {
            $query->where('name', 'like', '%' . $data['word'] . '%');
        }

        $query->orderBy('order', 'asc');

        if(isset($data['with_paginate']) && $data['with_paginate'] == 1) {
            $per_page = isset($data['limit']) ? $data['limit'] : 10;
            $all_course = $query->paginate($per_page);
            $response = CourseResource::collection($all_course)->response()->getData(true);
        }else {
            $all_course = $query->get();
            $response = CourseResource::collection($all_course);
        }
        return ApiResponseHelper::response(true, 'تم جلب جميع الكورسات بنجاح', $response);
    }
}

