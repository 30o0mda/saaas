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


        public function updateStage( $params) {
        $stage = stage::find($params['stage_id']);
        $stage->update($params);
        if(!empty($data['subject_ids'])) {
            $stage->subjects()->sync($params['subject_ids']);
        }
        return ApiResponseHelper::response(true, 'تم تحديث المرحلة بنجاح', [
         new StageResource($stage),]);
    }


        public function fetchStages( $params) {
        $stage = stage::find($params['stage_id']);
        return ApiResponseHelper::response(true, 'تم جلب المرحلة بنجاح', [
            'stage' => new StageResource($stage),]);
    }

        public function deleteStage( $params) {
        $stage = stage::find($params['stage_id']);
        $stage->delete();
        return ApiResponseHelper::response(true, 'تم حذف المرحلة بنجاح');
    }

}
