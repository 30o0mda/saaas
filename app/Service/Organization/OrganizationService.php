<?php


namespace App\Service\Organization;

use App\Helpers\ApiResponseHelper;
use App\Http\Enum\OrganizationEmployeEnum;
use App\Http\Resources\OrganizationResource;
use App\Models\organization;
use App\Params\Organization\CreateOrganizationTeacherParam;
use Exception;
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
        $paramsTeacher = new CreateOrganizationTeacherParam(
            name: $organization->name,
            email: $organization->email,
            phone: $organization->phone,
            password: $organization->phone,
            is_master: 1
        );
        // dd($paramsTeacher->toArray());
            $teacher = $organization->organization_employee()->create($paramsTeacher->toArray());
        // $assistant = $organization->organization_employee()->create([
        //     'name' => $organization->name . ' ' . OrganizationEmployeEnum::ASSISTANT->label(),
        //     'email' => $organization->name . $organization->surname . '@yahoo.com',
        //     'phone' => $organization->phone,
        //     'password' => Hash::make($organization->phone),
        //     'type' => OrganizationEmployeEnum::ASSISTANT,
        //     'parent_id' => $teacher->id
        // ]);
        return ApiResponseHelper::response(true, 'تم إنشاء المنظمة بنجاح', [
            'organization' => new OrganizationResource($organization, $teacher
        ),
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

