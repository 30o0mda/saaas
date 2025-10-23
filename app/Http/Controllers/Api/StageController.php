<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Stages\CreateStageRequest;
use App\Http\Requests\Stages\DeleteStageRequest;
use App\Http\Requests\Stages\FetchStageRequest;
use App\Http\Requests\Stages\UpdateStageRequest;
use App\Http\Resources\Stages\StageResource;
use App\Models\stage;
use Illuminate\Http\Request;

class StageController extends Controller
{
    public function createStage(CreateStageRequest $request) {
        $data = $request->validated();
        $data['organization_id'] = auth()->user()->organization_id ?? null;
        $data['parent_id'] = $data['parent_id'] ?? null;
        $stage = stage::create([
            'name' => $data['name'],
            'education_type_id' => $data['education_type_id'],
            'organization_id' => $data['organization_id'],
            'parent_id' => $data['parent_id'],
        ]);
        return ApiResponseHelper::response(true, 'تم إنشاء المرحلة بنجاح', [
            'stage' => new StageResource($stage),]);
    }

    public function updateStage(UpdateStageRequest $request) {
        $data = $request->validated();
        $stage = stage::find($data['stage_id']);
        $stage->update([
            'name' => $data['name'] ?? $stage->name,
            'education_type_id' => $data['education_type_id'] ?? $stage->education_type_id,
            'organization_id' => $data['organization_id'] ?? $stage->organization_id,
            'parent_id' => $data['parent_id'] ?? $stage->parent_id,
        ]);
        return ApiResponseHelper::response(true, 'تم تحديث المرحلة بنجاح', [
            'stage' => new StageResource($stage),]);
    }

    public function fetchStages(FetchStageRequest $request) {
        $data = $request->validated();
        $stage = stage::find($data['stage_id']);
        return ApiResponseHelper::response(true, 'تم جلب المرحلة بنجاح', [
            'stage' => new StageResource($stage),]);
    }

    public function deleteStage(DeleteStageRequest $request) {
        $data = $request->validated();
        $stage = stage::find($data['stage_id']);
        $stage->delete();
        return ApiResponseHelper::response(true, 'تم حذف المرحلة بنجاح');
    }
}
