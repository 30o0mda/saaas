<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Enum\OrganizationEmployeEnum;
use App\Http\Requests\CreateOrganizationRequest;
use App\Models\organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OrganizationController extends Controller
{
    public function createOrganization(CreateOrganizationRequest $request)
    {
        $data = $request->validated();
        $organization = organization::create($data);
        $teacher = $organization->organization_employe()->create([
            'name' => $organization->name . ' ' . OrganizationEmployeEnum::TEACHER->label(),
            'email' => $organization->name . $organization->surname . '@gmail.com',
            'phone' => $organization->phone,
            'password' => Hash::make($organization->phone),
            'type' => OrganizationEmployeEnum::TEACHER,
            'is_master' => 0
        ]);
        $assistant = $organization->organization_employe()->create([
            'name' => $organization->name . ' ' . OrganizationEmployeEnum::ASSISTANT->label(),
            'email' => $organization->name . $organization->surname . '@yahoo.com',
            'phone' => $organization->phone,
            'password' => Hash::make($organization->phone),
            'type' => OrganizationEmployeEnum::ASSISTANT,
            'parent_id' => $teacher->id
        ]);
        return response()->json([
            'message' => 'تم إنشاء المنظمة بنجاح',
            'organization' => $organization,
            'teacher' => $teacher,
            'assistant' => $assistant
        ]);
    }

    public function getOrganizations()
    {
        $organization = organization::all();
        return response()->json([
            'message' => 'تم الحصول على المنظمات بنجاح',
            'organizations' => $organization
        ]);
    }
}
