<?php

namespace App\Params\EducationType;

use Illuminate\Support\Facades\Hash;

class DeleteEducationTypeParam
{
    public string $education_type_id;



    public function __construct(
        int $education_type_id,


    ){
        $this->fromArray([
            'education_type_id' => $education_type_id,

        ]);
    }

    public function fromArray($data){

        $this->education_type_id = $data['education_type_id'];


    }

    public function toArray(){
        return [
            'education_type_id' => $this->education_type_id,
        ];
    }
}
