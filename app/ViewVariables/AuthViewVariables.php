<?php declare(strict_types=1);

namespace App\ViewVariables;

use App\Services\User\UserGetInformationService;

class AuthViewVariables implements ViewVariablesInterface
{
    public function getName(): string
    {
        return 'auth';
    }

    public function getValue(): array
    {
        if (!empty($_SESSION['auth_id'])) {
            $currentUser = (new UserGetInformationService())->execute($_SESSION['auth_id']);
            return [
                'id' => $currentUser->getId() ?? null,
                'email' => $currentUser->getEmail() ?? null,
                'name' => $currentUser->getName() ?? null,
                'fiatBalance' => $currentUser->getFiatBalance() ?? null,
            ];
        }
        return [];
    }
}