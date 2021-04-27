<?php


namespace App\Http\Controllers;


use App\Services\Api\UsersApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{

    private function getApiService(): UsersApiService
    {
        return app(UsersApiService::class);
    }

    public function show(Request $request): JsonResponse
    {
        $request->validate(array(
            'user_id' => 'required|int'
        ));

        $user = $this->getApiService()->getUser($request->get('user_id'));

        if (!$user) {
            abort(404);
        }

        return response()->json($user->toArray());
    }

    public function updateAvatar(Request $request): JsonResponse
    {
        $request->validate(array(
                'user_id' => 'required|int',
                'avatar' => 'string'
            )
        );

        $this->getApiService()->updateAvatar(
            $request->post('avatar'),
            $request->post('user_id')
        );

        return response()->json(array('avatar set' => true));
    }
}
