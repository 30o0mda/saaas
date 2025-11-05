<?php

namespace App\Params\OrganizationEmployee;

use Illuminate\Support\Facades\Hash;

class UpdateOrganizationEmployeeParam
{
    public string $name;
    public string $email;
    public string $phone;

    public int $organization_employee_id;



    public function __construct(
        string $name,
        string $email,
        string $phone,
        int $organization_employee_id

    ){
        $this->fromArray([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'organization_employee_id' => $organization_employee_id
        ]);
    }

    public function fromArray($data){

        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->phone = $data['phone'];
        $this->organization_employee_id = $data['organization_employee_id'];

    }

    public function toArray(){
        return [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'organization_employee_id' => $this->organization_employee_id
        ];
    }
}
