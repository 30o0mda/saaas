<?php

namespace App\Params\OrganizationEmployee;

use Illuminate\Support\Facades\Hash;

class CreateOrganizationEmployeeParam
{
    public string $name;
    public string $email;
    public string $phone;
    public int $type;
    public string $password;
    public int $organization_id;
    public ?int $parent_id;



    public function __construct(
        string $name,
        string $email,
        string $phone,
        string $password,
        ?int $parent_id,
        int $type

    ){
        $this->fromArray([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'password' => $password,
            'parent_id' => $parent_id,
            'type' => $type
        ]);
    }

    public function fromArray($data){

        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->phone = $data['phone'];
        $this->type = $data['type'];
        $this->password = Hash::make($data['password']);
        $this->organization_id = auth()->guard('organization')->user()->id;
        $this->parent_id = $data['parent_id'];

    }

    public function toArray(){
        return [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'type' => $this->type,
            'password' => $this->password,
            'organization_id' => $this->organization_id,
            'parent_id' => $this->parent_id


        ];
    }
}
