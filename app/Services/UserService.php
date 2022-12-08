<?php declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Repositories\User\UserRepository;

class UserService
{
    public function getUserData(?int $id = null, ?string $email = null): User
    {
        return (new UserRepository())->getUserData($id, $email);
    }
}