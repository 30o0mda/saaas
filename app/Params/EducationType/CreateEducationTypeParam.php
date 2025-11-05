<?php

namespace App\Params\EducationType;

use Illuminate\Support\Facades\Hash;

class CreateEducationTypeParam
{
    public string $name;
    public string $description;
    public int $organization_id;




    public function __construct(
        string $name,
        string $description,
    ){
        $this->fromArray([
            'name' => $name,
            'description' => $description,
        ]);
    }

    public function fromArray($data){
        $this->name = $data['name'];
        $this->description = $data['description'];
        $this->organization_id = auth()->guard('organization')->user()->id;
    }

    public function toArray(){
        return [
            'name' => $this->name,
            'description' => $this->description,
            'organization_id' => $this->organization_id,
        ];
    }
}
