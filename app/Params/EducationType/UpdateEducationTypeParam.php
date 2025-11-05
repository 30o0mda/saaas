<?php

namespace App\Params\EducationType;

use Illuminate\Support\Facades\Hash;

class UpdateEducationTypeParam
{
    public string $name;
    public string $description;
    public int $organization_id;
    public int $education_type_id;
    public bool $is_active;





    public function __construct(
        string $name,
        string $description,
        int $education_type_id,
        int $is_active
    ){
        $this->fromArray([
            'name' => $name,
            'description' => $description,
            'education_type_id' => $education_type_id,
            'is_active' => $is_active
        ]);
    }

    public function fromArray($data){
        $this->name = $data['name'];
        $this->description = $data['description'];
        $this->education_type_id = $data['education_type_id'];
        $this->is_active = $data['is_active'];
    }

    public function toArray(){
        return [
            'name' => $this->name,
            'description' => $this->description,
            'is_active' => (int)$this->is_active,
            'education_type_id' => $this->education_type_id
        ];
    }
}


