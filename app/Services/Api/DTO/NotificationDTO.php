<?php


namespace App\Services\Api\DTO;


class NotificationDTO
{

    private $id;
    private $message;
    private $userId;


    public function __construct(
        $id,
        $message,
        $userId
    )
    {
        $this->id = $id;
        $this->message = $message;
        $this->userId = $userId;
    }

    public function toArray(): array
    {
        return array(
            'id' => $this->id,
            'message' => $this->message,
            'userId' => $this->userId,
        );
    }
}
