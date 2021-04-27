<?php


namespace App\Services\Api\Translators;


use App\Models\Notification;
use App\Services\Api\DTO\NotificationDTO;

class NotificationTranslator
{
    public function translate(Notification $notification): NotificationDTO
    {
        return new NotificationDTO(
            $notification->id,
            $notification->message,
            $notification->user_id,
        );
    }
}
