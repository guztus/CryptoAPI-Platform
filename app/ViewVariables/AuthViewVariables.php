<?php declare(strict_types=1);

namespace App\ViewVariables;

use App\Services\UserService;

class AuthViewVariables implements ViewVariablesInterface
{
    public function getName(): string
    {
        return 'auth';
    }

    public function getValue(): array
    {
        if (!empty($_SESSION['auth_id'])) {
            $currentUser = (new UserService())->getUserData($_SESSION['auth_id']);
            return [
                'id' => $currentUser->getId() ?? null,
                'email' => $currentUser->getEmail() ?? null,
                'name' => $currentUser->getName() ?? null,
            ];
        }
        return [];
    }
}