<?php

namespace App\Params\OrganizationEmployee;

class LoginOrganizationEmployeeParam
{
    protected $credentials;
    protected $password;
    protected $email;
    protected $phone;

    public function __construct(
        $credentials,
        $password
    ){
        $this->fromArray([
            'credentials' => $credentials,
            'password' => $password
        ]);
    }

    protected function fromArray($data){
        if(filter_var($data['credentials'], FILTER_VALIDATE_EMAIL)){
            $this->email = $data['credentials'];
        }else{
            $this->phone = $data['credentials'];
        }
        $this->password = $data['password'];
    }


    public function toArray(){
        return [
            'email' => $this->email ,
            'phone' => $this->phone,
            'password' => $this->password
        ];
    }
}
