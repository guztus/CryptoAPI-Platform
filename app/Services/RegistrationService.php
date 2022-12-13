<?php declare(strict_types=1);

namespace App\Services;

use App\Repositories\Registration\RegistrationRepository;

class RegistrationService
{
    public function execute(
        string $name,
        string $email,
        string $password)
    {
        (new RegistrationRepository())
            ->save($name, $email, $password);
    }
}