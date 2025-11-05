<?php

namespace App\Params\Organization;

use App\Http\Enum\OrganizationEmployeEnum;
use App\Http\Enum\OrganizationEnum;
use Illuminate\Support\Facades\Hash;
use Psy\CodeCleaner\CalledClassPass;


class CreateOrganizationTeacherParam
{

    protected $name;
    protected $email;
    protected $phone;
    protected $password;
    protected $type;
    protected $admin_id;

    protected $is_master;

    public function __construct(
        string $name,
        string $email,
        string $phone,
        string $password,
        int $is_master

    ) {
        $this->fromArray([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'password' => $password,
            'is_master' => $is_master
        ]);
    }

    public function fromArray($data)
    {
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->phone = $data['phone'];
        $this->password = Hash::make($data['password']);
        $this->type = OrganizationEmployeEnum::TEACHER->value;
        $this->admin_id =auth()->guard('admin')->user()->id;
        $this->is_master = 1;
    }

    public function toArray()
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'password' => $this->password,
            'type' => $this->type,
            'admin_id' => $this->admin_id,
            'is_master' => $this->is_master

        ];
    }
}
