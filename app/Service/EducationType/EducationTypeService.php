<?php

namespace App\Service\EducationType;

use App\Helpers\ApiResponseHelper;
use App\Http\Resources\EducationType\EducationTypeResource;
use App\Models\EducationType;

class EducationTypeService
{
    public function __construct()
    {

    }
        public function createEducationType( $params) {
        $education_type = EducationType::create($params);
        $education_type->refresh();
        return ApiResponseHelper::response(true, 'تم إنشاء نوع التعليم بنجاح',[
            'education_type' => new EducationTypeResource($education_type)]);
    }

        public function updateEducationType( $params) {
        $education_type = EducationType::find($params['education_type_id']);
        $education_type->update($params);
        return ApiResponseHelper::response(true, 'تم تحديث نوع التعليم بنجاح',[
            'education_type' => new EducationTypeResource($education_type)]);
    }

        public function fetchEducationTypes( $params) {
        $education_type = EducationType::find($params['education_type_id']);
        return ApiResponseHelper::response(true, 'تم جلب نوع التعليم بنجاح',[
            'education_type' => new EducationTypeResource($education_type)]);
    }

        public function deleteEducationType( $Params) {
        $education_type = EducationType::find($Params['education_type_id']);
        $education_type->delete();
        return ApiResponseHelper::response(true, 'تم حذف نوع التعليم بنجاح' ,[
            'education_type' => new EducationTypeResource($education_type)
        ]);
    }
}
