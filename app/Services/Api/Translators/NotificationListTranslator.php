<?php


namespace App\Services\Api\Translators;


use App\Models\Notification;
use Illuminate\Database\Eloquent\Collection;

class NotificationListTranslator
{
    private $notificationTranslator;

    public function __construct(
        NotificationTranslator $notificationTranslator
    )
    {
        $this->notificationTranslator = $notificationTranslator;
    }

    public function translate(int $limit, int $offset): array
    {
        $notifications = $this->getNotifications($limit, $offset);
        $result = array();

        foreach ($notifications as $notification) {
            $result[] = $this->notificationTranslator->translate($notification)->toArray();
        }

        return $result;
    }

    private function getNotifications(int $limit, int $offset): Collection
    {
        $notifications = Notification::query()
            ->limit($limit)
            ->skip($offset)
            ->get();

        return $notifications;
    }
}
