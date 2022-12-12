<?php declare(strict_types=1);

namespace App\Services\User;

use App\Models\User;
use App\Repositories\User\UserRepository;

class UserService
{
    public function getUserData(?int $id = null, ?string $email = null): User
    {
        return (new UserRepository())->getUserData($id, $email);
    }

    public function modifyFiatBalance(int $id, float $fiatAmount, string $operation): void
    {
        (new UserRepository())->modifyFiatBalance($id, $fiatAmount, $operation);
    }
}