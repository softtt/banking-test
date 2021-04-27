<?php


namespace App\Services\Api;


use App\Models\User;
use App\Services\Api\DTO\UserDTO;
use App\Services\Api\Translators\UserListTranslator;
use App\Services\Api\Translators\UserTranslator;
use http\Exception\RuntimeException;

class UsersApiService
{

    private $userListTranslator;
    private $userTranslator;

    public function __construct(
        UserListTranslator $userListTranslator,
        UserTranslator $userTranslator
    )
    {
        $this->userTranslator = $userTranslator;
        $this->userListTranslator = $userListTranslator;
    }

    public function getUser(int $userId): ?UserDTO
    {
        $user = User::find($userId);

        if (!$user) {
            return null;
        }

        return $this->userTranslator->translate($user);
    }

    public function updateAvatar(string $avatar, int $userId): void
    {
        $user = User::find($userId);

        if (!$user) {
            throw new RuntimeException('User not found');
        }

        $user->avatar = $avatar;
        $user->save();
    }
}
