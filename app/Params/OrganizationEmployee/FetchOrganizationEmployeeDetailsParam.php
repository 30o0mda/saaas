<?php

namespace App\Params\OrganizationEmployee;

use Illuminate\Support\Facades\Hash;

class FetchOrganizationEmployeeDetailsParam
{
    public string $organization_employee_id;



    public function __construct(
        string $organization_employee_id,


    ){
        $this->fromArray([
            'organization_employee_id' => $organization_employee_id,

        ]);
    }

    public function fromArray($data){

        $this->organization_employee_id = $data['organization_employee_id'];


    }

    public function toArray(){
        return [
            'organization_employee_id' => $this->organization_employee_id,
        ];
    }
}
