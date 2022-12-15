<?php

namespace App\Services\User;

use App\Models\User;
use App\Repositories\User\UserRepository;

class UserGetInformationService
{
    public function execute(
        ?int    $id = null,
        ?string $email = null
    ): ?User
    {
        return (new UserRepository())->getUserData($id, $email);
    }
}