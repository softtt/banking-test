<?php


namespace App\Services\Api;


use App\Models\Notification;
use App\Services\Api\Translators\NotificationListTranslator;
use App\Services\Api\Translators\NotificationTranslator;

class NotificationService
{
    private $notificationTranslator;
    private $notificationListTranslator;

    public function __construct(
        NotificationTranslator $notificationTranslator,
        NotificationListTranslator $notificationListTranslator
    )
    {
        $this->notificationTranslator = $notificationTranslator;
        $this->notificationListTranslator = $notificationListTranslator;
    }

    public function getUserNotifications(int $userId): array
    {
        $notifications = Notification::query()
            ->where('user_id', '=', $userId)
            ->get();

        $result = array();

        foreach ($notifications as $notification) {
            $result[] = $this->notificationTranslator->translate($notification)->toArray();
        }

        return $result;
    }
}
