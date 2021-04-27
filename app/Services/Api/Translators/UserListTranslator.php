<?php


namespace App\Services\Api\Translators;


use App\Models\User;
use Illuminate\Support\Collection;

class UserListTranslator
{
    private $userTranslator;

    public function __construct()
    {
        $this->userTranslator = new UserTranslator();
    }

    public function translate(int $limit, int $offset): array
    {
        $users = $this->getUsers($limit, $offset);
        $result = array();

        foreach ($users as $user) {
            $result[] = $this->userTranslator->translate($user)->toArray();
        }

        return $result;
    }

    private function getUsers(int $limit, int $offset) : Collection
    {
        $users = User::query()
            ->limit($limit)
            ->skip($offset)
            ->get();

        return $users;
    }
}
