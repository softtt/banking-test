<?php


namespace App\Services\Api\Translators;


use App\Models\User;
use App\Services\Api\DTO\UserDTO;

class UserTranslator
{
    public function translate(User $user): UserDTO
    {
        return new UserDTO(
            $user->id,
            $user->name,
            $user->avatar
        );
    }
}
