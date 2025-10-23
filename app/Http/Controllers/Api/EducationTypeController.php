<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\EducationType\CreateEducationTypeRequest;
use App\Http\Requests\EducationType\DeleteEducationTypeRequest;
use App\Http\Requests\EducationType\UpdateEducationTypeRequest;
use App\Http\Requests\EducationType\FetchEducationTypeRequest;
use App\Http\Resources\EducationType\EducationTypeResource;
use App\Models\EducationType;
use Illuminate\Http\Request;

class EducationTypeController extends Controller
{
    public function createEducationType(CreateEducationTypeRequest $request) {
        $date = $request->validated();
        $date['organization_id'] = auth()->user()->organization_id ?? null;
        $date['is_active'] = $date['is_active'] ?? 1;
        $education_type = EducationType::create($date);
        return ApiResponseHelper::response(true, 'تم إنشاء نوع التعليم بنجاح',[
            'education_type' => new EducationTypeResource($education_type)]);
    }

    public function updateEducationType(UpdateEducationTypeRequest $request) {
        $date = $request->validated();
        $education_type = EducationType::find($date['education_type_id']);
        $education_type->update([
            'name' => $date['name'] ?? $education_type->name,
            'description' => $date['description'] ?? $education_type->description,
            'organization_id' => $date['organization_id'] ?? $education_type->organization_id,
            'is_active' => $date['is_active'] ?? $education_type->is_active
        ]);
        return ApiResponseHelper::response(true, 'تم تحديث نوع التعليم بنجاح',[
            'education_type' => new EducationTypeResource($education_type)]);
    }

    public function fetchEducationTypes(FetchEducationTypeRequest $request) {
        $date = $request->validated();
        $education_types = EducationType::where('organization_id',auth()->user()->organization_id)->get();
        return ApiResponseHelper::response(true, 'تم جلب [education_types] نوع التعليم بنجاح',[
            'education_types' => EducationTypeResource::collection($education_types)]);
    }

    public function deleteEducationType(DeleteEducationTypeRequest $request) {
        $date = $request->validated();
        $education_type = EducationType::find($date['education_type_id']);
        $education_type->delete();
        return ApiResponseHelper::response(true, 'تم حذف نوع التعليم بنجاح');
    }
}
