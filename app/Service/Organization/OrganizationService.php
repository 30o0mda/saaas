<?php


namespace App\Service\Organization;

use App\Helpers\ApiResponseHelper;
use App\Http\Enum\OrganizationEmployeEnum;
use App\Http\Resources\OrganizationResource;
use App\Models\organization;
use Illuminate\Support\Facades\Hash;


class OrganizationService
{
    public function __construct()
    {
    }


      public function createOrganization($params)
    {
        $organization = organization::create($params);
        $organization->update([
            'admin_id' => $params['admin_id']
        ]);
        // $teacher = $organization->organization_employee()->create([
        //     'name' => $organization->name . ' ' . OrganizationEmployeEnum::TEACHER->label(),
        //     'email' => $organization->email,
        //     'phone' => $organization->phone,
        //     'password' => Hash::make($organization->phone),
        //     'type' => OrganizationEmployeEnum::TEACHER,
        //     'is_master' => 1 ,
        //     'admin_id' => auth()->user()->id ?? null
        // ]);
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


    public function getOrganizations()
    {
        $organizations = organization::all();
        return ApiResponseHelper::response(true, 'تم جلب المنظمات بنجاح', [
            'organization' => OrganizationResource::collection($organizations),
        ]);
    }
}

