<?php

namespace App\Params\Organization;

class GetOrganizationParam
{
    protected $organization_id;
    public function __construct(
        int $organization_id,
    ) {
        $this->fromArray([
            'organization_id' => $organization_id
        ]);
    }

    public function fromArray($data)
    {
        $this->organization_id = $data['organization_id'];
    }
    public function toArray()
    {
        return [
            'organization_id' => $this->organization_id,
        ];
    }
}
