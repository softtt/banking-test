<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Services\Api\CurrencyService;
use App\Services\Api\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * @return NotificationService
     */
    private function getApiService(): NotificationService
    {
        return app(NotificationService::class);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getUserNotifications(Request $request): JsonResponse
    {
        $request->validate(array(
            'user_id' => 'required|int'
        ));

        $notifications = $this->getApiService()->getUserNotifications($request->get('user_id'));

        return response()->json($notifications);
    }
}
