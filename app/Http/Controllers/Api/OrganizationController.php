<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Enum\OrganizationEmployeEnum;
use App\Http\Requests\CreateOrganizationRequest;
use App\Http\Resources\OrganizationEmployeeResource;
use App\Http\Resources\OrganizationResource;
use App\Models\organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


/**
 * @OA\Tag(
 *     name="Organizations",
 *     description="Endpoints for managing organizations"
 * )
 */
class OrganizationController extends Controller
{
    /**
     * @OA\Post(
     *     path="/create_organization",
     *     summary="إنشاء منظمة جديدة",
     *     tags={"Organizations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","phone","email","type"},
     *             @OA\Property(property="name", type="string", example="مدرسة النور"),
     *             @OA\Property(property="phone", type="string", example="+201234567890"),
     *             @OA\Property(property="email", type="string", format="email", example="info@school.com"),
     *             @OA\Property(property="type", type="string", example="مدير"),
     *             @OA\Property(property="is_master", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="تم إنشاء المنظمة بنجاح",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="تم إنشاء المنظمة بنجاح"),
     *             @OA\Property(property="organization", ref="#/components/schemas/OrganizationResource")
     *         )
     *     )
     * )
     */


    public function createOrganization(CreateOrganizationRequest $request)
    {
        $data = $request->validated();
        $organization = organization::create($data);
        $organization->update([
            'admin_id' => auth()->user()->id
        ]);
        $teacher = $organization->organization_employee()->create([
            'name' => $organization->name . ' ' . OrganizationEmployeEnum::TEACHER->label(),
            'email' => $organization->email,
            'phone' => $organization->phone,
            'password' => Hash::make($organization->phone),
            'type' => OrganizationEmployeEnum::TEACHER,
            'is_master' => 2 ,
            'admin_id' => auth()->user()->id ?? null
        ]);
        // $assistant = $organization->organization_employee()->create([
        //     'name' => $organization->name . ' ' . OrganizationEmployeEnum::ASSISTANT->label(),
        //     'email' => $organization->name . $organization->surname . '@yahoo.com',
        //     'phone' => $organization->phone,
        //     'password' => Hash::make($organization->phone),
        //     'type' => OrganizationEmployeEnum::ASSISTANT,
        //     'parent_id' => $teacher->id
        // ]);
        return ApiResponseHelper::response(true, 'تم إنشاء المنظمة بنجاح', [
            'organization' => new OrganizationResource($organization),
        ]);
    }

       /**
     * @OA\Get(
     *     path="/get_organizations",
     *     summary="جلب كل المنظمات",
     *     tags={"Organizations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="تم جلب المنظمات بنجاح",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="تم جلب المنظمات بنجاح"),
     *             @OA\Property(
     *                 property="organization",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/OrganizationResource")
     *             )
     *         )
     *     )
     * )
     */

    public function getOrganizations()
    {
        $organizations = organization::all();
        return ApiResponseHelper::response(true, 'تم جلب المنظمات بنجاح', [
            'organization' => OrganizationResource::collection($organizations),
        ]);
    }
}
