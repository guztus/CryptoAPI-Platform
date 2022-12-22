<?php declare(strict_types=1);

namespace App\Services;

use App\Repositories\Registration\RegistrationRepository;
use App\Validator;

class RegistrationService
{
    public function execute(
        string $name,
        string $email,
        string $password)
    {
        (new Validator())->register(
            $_POST['email'],
            $_POST['password'],
            $_POST['password_confirmation']
        );

        if (!Validator::passed()) {
            return;
        }
        $_SESSION['alerts']['success'] [] = 'Registration successful!';


        (new RegistrationRepository())
            ->save($name, $email, $password);
    }
}