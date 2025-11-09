<?php

namespace App\Params\Stage;

class UpdataStageParam {
    public string $name;
    public ?int $parent_id;
    public string $description;
    public array $subject_ids;
    public int $education_type_id;
    public int $stage_id;


    public function __construct(
        string $name,
        string $description,
        int $education_type_id,
        array $subject_ids,
        ?int $parent_id,
        int $stage_id
    ){
        $this->fromArray([
            'name' => $name,
            'description' => $description,
            'education_type_id' => $education_type_id,
            'subject_ids' => $subject_ids,
            'parent_id' => $parent_id,
            'stage_id' => $stage_id
        ]);
    }

    public function fromArray($data){
        $this->name = $data['name'];
        $this->description = $data['description'];
        $this->education_type_id = $data['education_type_id'];
        $this->subject_ids = $data['subject_ids'];
        $this->parent_id = $data['parent_id'];
        $this->stage_id = $data['stage_id'];

    }

    public function toArray(){
        return [
            'name' => $this->name,
            'description' => $this->description,
            'education_type_id' => $this->education_type_id,
            'subject_ids' => $this->subject_ids,
            'parent_id' => $this->parent_id,
            'stage_id' => $this->stage_id
        ];
    }
}
