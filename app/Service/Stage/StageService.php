<?php

namespace App\Service\Stage;

use App\Helpers\ApiResponseHelper;
use App\Http\Resources\Stages\StageResource;
use App\Models\Stage;

class StageService
{
    public function __construct()
    {
    }
       public function createStage( $params) {
        $stage = Stage::create($params);
        if(!empty($params['subject_ids'])) {
            $stage->subjects()->attach($params['subject_ids']);
        }
        return ApiResponseHelper::response(true, 'تم إنشاء المرحلة بنجاح', [
            'stage' => new StageResource($stage)]);
    }


        public function updateStage( $data) {
        $stage = stage::find($data['stage_id']);
        $stage->update([
            'name' => $data['name'] ?? $stage->name,
            'education_type_id' => $data['education_type_id'] ?? $stage->education_type_id,
            'organization_id' => $data['organization_id'] ?? $stage->organization_id,
            'parent_id' => $data['parent_id'] ?? $stage->parent_id,
        ]);
        if(!empty($data['subject_ids'])) {
            $stage->subjects()->sync($data['subject_ids']);
        }
        return ApiResponseHelper::response(true, 'تم تحديث المرحلة بنجاح', [
            'stage' => new StageResource($stage),]);
    }


        public function fetchStages( $data) {
        $stage = stage::find($data['stage_id']);
        return ApiResponseHelper::response(true, 'تم جلب المرحلة بنجاح', [
            'stage' => new StageResource($stage),]);
    }

        public function deleteStage( $data) {
        $stage = stage::find($data['stage_id']);
        $stage->delete();
        return ApiResponseHelper::response(true, 'تم حذف المرحلة بنجاح');
    }

}
