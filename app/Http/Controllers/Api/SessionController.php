<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Enum\SessionEnum;
use App\Http\Requests\Sessions\CreateSessionRequest;
use App\Http\Requests\Sessions\DeleteSessionRequest;
use App\Http\Requests\Sessions\FetchAllSessionRequest;
use App\Http\Requests\Sessions\FetchSessionRequest;
use App\Http\Requests\Sessions\IsActiveSessionRequest;
use App\Http\Requests\Sessions\orderSessionRequest;
use App\Http\Requests\Sessions\UpdateSessionRequest;
use App\Http\Resources\Sessions\SessionResource;
use App\Models\course;
use App\Models\Session;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Sessions",
 *     description="API Endpoints for Sessions Management"
 * )
 */
class SessionController extends Controller
{
    /**
     * @OA\Post(
     *     path="/create_session",
     *     tags={"Sessions"},
     *     summary="إنشاء جلسة جديدة",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","type"},
     *             @OA\Property(property="name", type="string", example="جلسة 1"),
     *             @OA\Property(property="description", type="string", example="تفاصيل الجلسة"),
     *             @OA\Property(property="parent_id", type="integer", nullable=true, example=null),
     *             @OA\Property(property="type", type="string", example="video"),
     *             @OA\Property(property="link", type="string", example="https://link.com"),
     *             @OA\Property(property="file", type="string", example="file.pdf"),
     *             @OA\Property(property="course_id", type="integer", example=1),
     *             @OA\Property(property="is_active", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="تم إضافة الجلسة بنجاح",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="تم إضافة الجلسة بنجاح"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="session", ref="#/components/schemas/Session")
     *             )
     *         )
     *     ),

     * )
     */
    public function createSession(CreateSessionRequest $request)
    {
        $data = $request->validated();
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



    /**
     * @OA\post(
     *     path="/update_session",
     *     tags={"Sessions"},
     *     summary="تحديث جلسة موجودة",
     *     @OA\Parameter(
     *         name="session_id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="معرف الجلسة"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="parent_id", type="integer", nullable=true),
     *             @OA\Property(property="type", type="string"),
     *             @OA\Property(property="link", type="string"),
     *             @OA\Property(property="file", type="string"),
     *             @OA\Property(property="course_id", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="تم تحديث الجلسة بنجاح",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="تم تحديث الجلسة بنجاح"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="session", ref="#/components/schemas/Session")
     *             )
     *         )
     *     ),
     * )
     */
    public function updateSession(UpdateSessionRequest $request)
    {
        $data = $request->validated();
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

        /**
     * @OA\post(
     *     path="/fetch_session_details",
     *     tags={"Sessions"},
     *     summary="جلب تفاصيل الجلسة",
     *     @OA\Parameter(
     *         name="session_id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="معرف الجلسة"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="تم جلب الجلسة بنجاح",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="تم جلب الجلسة بنجاح"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="session", ref="#/components/schemas/Session")
     *             )
     *         )
     *     ),

     * )
     */

    public function fetchSessionDetails(FetchSessionRequest $request)
    {
        $data = $request->validated();
        $session = Session::find($data['session_id']);
        return ApiResponseHelper::response(true, 'تم جلب الجلسة بنجاح', [
            'session' => new SessionResource($session),
        ]);
    }


        /**
     * @OA\post(
     *     path="/delete_session",
     *     tags={"Sessions"},
     *     summary="حذف جلسة",
     *     @OA\Parameter(
     *         name="session_id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="معرف الجلسة"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="تم حذف الجلسة بنجاح",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="تم حذف الجلسة بنجاح")
     *         )
     *     ),
     * )
     */

    public function deleteSession(DeleteSessionRequest $request)
    {
        $data = $request->validated();
        $session = Session::find($data['session_id']);
        $session->delete();
        return ApiResponseHelper::response(true, 'تم حذف الجلسة بنجاح');
    }


        /**
     * @OA\post(
     *     path="/is_active_session",
     *     tags={"Sessions"},
     *     summary="تفعيل/تعطيل الجلسة",
     *     @OA\Parameter(
     *         name="session_id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="معرف الجلسة"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="تم تغيير حالة الجلسة بنجاح",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="تم تغيير حالة الجلسة بنجاح"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="session", ref="#/components/schemas/Session")
     *             )
     *         )
     *     ),
     * )
     */

    public function isActiveSession(IsActiveSessionRequest $request)
    {
        $data = $request->validated();
        $session = Session::find($data['session_id']);
        $is_active = $session->is_active;
        $session->update([
            'is_active' => !$is_active
        ]);
        return ApiResponseHelper::response(true, 'تم تغيير حالة الجلسة بنجاح', [
            'session' => new SessionResource($session),
        ]);
    }


        /**
     * @OA\post(
     *     path="/order_session",
     *     tags={"Sessions"},
     *     summary="تغيير ترتيب الجلسة",
     *     @OA\Parameter(
     *         name="session_id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="معرف الجلسة"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"order"},
     *             @OA\Property(property="order", type="integer", example=3)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="تم تغيير ترتيب الجلسة بنجاح",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="تم تغيير ترتيب الجلسة بنجاح"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="session", ref="#/components/schemas/Session")
     *             )
     *         )
     *     ),
     * )
     */

    public function orderSession(orderSessionRequest $request)
    {
        $data = $request->validated();
        $session = Session::find($data['session_id']);
        $session->update([
            'order' => $data['order']
        ]);
        Session::where('organization_id', auth()->user()->organization_id)->where('order', '>=', $data['order'])->increment('order');
        return ApiResponseHelper::response(true, 'تم تغيير ترتيب الجلسة بنجاح', [
            'session' => new SessionResource($session),
        ]);
    }

       /**
     * @OA\post(
     *     path="/fetch_all_sessions",
     *     tags={"Sessions"},
     *     summary="جلب جميع الجلسات",
     *     @OA\Parameter(
     *         name="course_id",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="معرف الكورس"
     *     ),
     *     @OA\Parameter(
     *         name="word",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string"),
     *         description="للبحث في اسم الجلسة"
     *     ),
     *     @OA\Parameter(
     *         name="with_paginate",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="boolean", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="تم جلب جميع الجلسات بنجاح",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="تم جلب جميع الجلسات بنجاح"),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Session"))
     *         )
     *     )
     * )
     */

    public function fetchAllSessions(FetchAllSessionRequest $request)
    {
        $data = $request->validated();
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
