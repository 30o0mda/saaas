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
            'name' => $organization->name,
            'email' => $organization->email,
            'phone' => $organization->phone,
            'password' => Hash::make($organization->phone),
            'type' => OrganizationEmployeEnum::TEACHER
        ]);
        $assistant = $organization->organization_employe()->create([
            'name' => $organization->name,
            'email' => $organization->email,
            'phone' => $organization->phone,
            'password' => Hash::make($organization->phone),
            'type' => OrganizationEmployeEnum::ASSISTANT
        ]);
        return response()->json([
            'message' => 'تم إنشاء المنظمة بنجاح',
            'organization' => $organization,
            'teacher' => $teacher,
            'assistant' => $assistant
        ]);
    }
}
