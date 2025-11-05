<?php

namespace App\Params\OrganizationEmployee;

use Illuminate\Support\Facades\Hash;

class FetchOrganizationEmployeeParam
{
    public string $type;



    public function __construct(
        string $type,


    ){
        $this->fromArray([
            'type' => $type,

        ]);
    }

    public function fromArray($data){

        $this->type = $data['type'];


    }

    public function toArray(){
        return [
            'type' => $this->type,
        ];
    }
}
