<?php


namespace App\Services\Api\DTO;


class UserDTO
{
    private $id;
    private $name;
    private $avatar;

    public function __construct(
        $id,
        $name,
        $avatar
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->avatar = $avatar;
    }

    public function toArray(): array
    {
        return array(
            'id' => $this->id,
            'name' => $this->name,
            'avatar' => $this->avatar,
        );
    }
}
