<?php

namespace App\Params\Organization;

class CreateOrganizationParam
{
    protected string $name;
    protected string $email;
    protected string $phone;
    protected int $type;
    protected int $max_teachers;
    protected int $admin_id;



    public function __construct(
        string $name,
        string $email,
        string $phone,
        int $type,
        int $max_teachers,
    ){
        $this->fromArray([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'type'=> $type,
            'max_teachers'=> $max_teachers,
        ]);
    }

    public function fromArray($data){

        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->phone = $data['phone'];
        $this->type = $data['type'];
        $this->max_teachers = $data['max_teachers'];
        $this->admin_id = auth()->user()->id;
    }

    public function toArray(){
        return [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'type' => $this->type,
            'max_teachers' => $this->max_teachers,
            'admin_id'=> $this->admin_id
        ];
    }
}
