<?php

namespace App\Params\Stage;

use Illuminate\Support\Facades\Hash;

class FetchStageParam
{
    public string $stage_id;



    public function __construct(
        int $stage_id,


    ){
        $this->fromArray([
            'stage_id' => $stage_id,

        ]);
    }

    public function fromArray($data){
        $this->stage_id = $data['stage_id'];
    }

    public function toArray(){
        return [
            'stage_id' => $this->stage_id,
        ];
    }
}
