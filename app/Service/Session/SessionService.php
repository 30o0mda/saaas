<?php

namespace App\Service\Session;

use App\Helpers\ApiResponseHelper;
use App\Http\Enum\SessionEnum;
use App\Http\Resources\Sessions\SessionResource;
use App\Models\Session;

class SessionService
{
    public function __construct()
    {
    }

        public function createSession( $data)
    {
        if (!empty($data['parent_id'] ?? null)) {
            $parentSession = Session::findOrFail($data['parent_id']);
            $data['course_id'] = $parentSession->course_id;
        }
        if (empty($data['parent_id']) && empty($data['course_id'])) {
            return ApiResponseHelper::response(false, 'يجب تحديد course_id عند عدم وجود parent_id');
        }
        if (empty($data['parent_id'] ?? null)) {
            $order = Session::whereNull('parent_id')->where('course_id', $data['course_id'])->max('order') + 1;
        } else {
            $session = Session::find($data['parent_id']);
            $order = Session::where('parent_id', $data['parent_id'])->max('order') + 1;
        }
        $session = Session::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'parent_id' => $data['parent_id'] ?? null,
            'type' => SessionEnum::from($data['type'])->value,
            'link' => $data['link'] ?? null,
            'file' => $data['file'] ?? null,
            'course_id' => $data['course_id'],
            'is_active' => $data['is_active'] ?? 1,
            'order' => $order,
            'organization_id' => auth()->user()->organization_id,
        ]);
        return ApiResponseHelper::response(true, 'تم إضافة الجلسة بنجاح', [
            'session' => new SessionResource($session),
        ]);
    }



        public function updateSession( $data)
    {
        $session = Session::find($data['session_id']);
        $session->update([
            'name' => $data['name'] ?? $session->name,
            'description' => $data['description'] ?? $session->description,
            'parent_id' => $data['parent_id'] ?? $session->parent_id,
            'type' => SessionEnum::from($data['type'])->value ?? $session->type,
            'link' => $data['link'] ?? $session->link,
            'file' => $data['file'] ?? $session->file,
            'course_id' => $data['course_id'] ?? $session->course_id,
        ]);
        return ApiResponseHelper::response(true, 'تم تحديث الجلسة بنجاح', [
            'session' => new SessionResource($session),
        ]);
    }

        public function fetchSessionDetails( $data)
    {
        $session = Session::find($data['session_id']);
        return ApiResponseHelper::response(true, 'تم جلب الجلسة بنجاح', [
            'session' => new SessionResource($session),
        ]);
    }


       public function deleteSession( $data)
    {
        $session = Session::find($data['session_id']);
        $session->delete();
        return ApiResponseHelper::response(true, 'تم حذف الجلسة بنجاح');
    }


        public function isActiveSession( $data)
    {
        $session = Session::find($data['session_id']);
        $is_active = $session->is_active;
        $session->update([
            'is_active' => !$is_active
        ]);
        return ApiResponseHelper::response(true, 'تم تغيير حالة الجلسة بنجاح', [
            'session' => new SessionResource($session),
        ]);
    }


        public function orderSession( $data)
    {
        $session = Session::find($data['session_id']);
        $session->update([
            'order' => $data['order']
        ]);
        Session::where('organization_id', auth()->user()->organization_id)->where('order', '>=', $data['order'])->increment('order');
        return ApiResponseHelper::response(true, 'تم تغيير ترتيب الجلسة بنجاح', [
            'session' => new SessionResource($session),
        ]);
    }


        public function fetchAllSessions( $data)
    {
        $query = session::where('organization_id', auth()->user()->organization_id);
        if (isset($data['word'])) {
            $query->where('name', 'like', '%' . $data['word'] . '%');
        }
        $query->orderBy('order', 'asc');
        if (isset($data['with_paginate']) && $data['with_paginate'] == 1) {
            $per_page = isset($data['limit']) ? $data['limit'] : 10;
            $all_session = $query->paginate($per_page);
            $response = SessionResource::collection($all_session)->response()->getData(true);
        } else {
            $all_session = $query->get();
            $response = SessionResource::collection($all_session);
        }
        return ApiResponseHelper::response(true, 'تم جلب جميع الجلسات بنجاح', $response);
    }
}
