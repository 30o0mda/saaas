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
use App\Service\Session\SessionService;
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

    protected $sessionService;

    public function __construct(SessionService $sessionService)
    {
        $this->sessionService = $sessionService;
    }

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
     *                 @OA\Property(property="session", ref="#/components/schemas/SessionResource")
     *             )
     *         )
     *     ),

     * )
     */
    public function createSession(CreateSessionRequest $request)
    {
        $data = $request->validated();

        return $this->sessionService->createSession($data)->getData();
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
     *                 @OA\Property(property="session", ref="#/components/schemas/SessionResource")
     *             )
     *         )
     *     ),
     * )
     */
    public function updateSession(UpdateSessionRequest $request)
    {
        $data = $request->validated();
        return $this->sessionService->updateSession($data)->getData();
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
     *                 @OA\Property(property="session", ref="#/components/schemas/SessionResource")
     *             )
     *         )
     *     ),

     * )
     */

    public function fetchSessionDetails(FetchSessionRequest $request)
    {
        $data = $request->validated();
        return $this->sessionService->fetchSessionDetails($data)->getData();
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
        return $this->sessionService->deleteSession($data)->getData();
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
     *                 @OA\Property(property="session", ref="#/components/schemas/SessionResource")
     *             )
     *         )
     *     ),
     * )
     */

    public function isActiveSession(IsActiveSessionRequest $request)
    {
        $data = $request->validated();
        return $this->sessionService->isActiveSession($data)->getData();
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
     *                 @OA\Property(property="session", ref="#/components/schemas/SessionResource")
     *             )
     *         )
     *     ),
     * )
     */

    public function orderSession(orderSessionRequest $request)
    {
        $data = $request->validated();
        return $this->sessionService->orderSession($data)->getData();
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
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/SessionResource"))
     *         )
     *     )
     * )
     */

    public function fetchAllSessions(FetchAllSessionRequest $request)
    {
        $data = $request->validated();
        return $this->sessionService->fetchAllSessions($data)->getData();
    }
}
