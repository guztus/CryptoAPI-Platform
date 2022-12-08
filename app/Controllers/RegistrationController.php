<?php declare(strict_types=1);

namespace App\Controllers;

use App\Redirect;
use App\Services\RegistrationService;
use App\Template;
use App\Validator;

class RegistrationController
{
    public function showForm(): Template
    {
        return new Template('/registration/registration.view.twig');
    }

    public function register(): Redirect
    {
        (new Validator())->register($_POST['email'], $_POST['password'], $_POST['password_confirmation']);

        if (Validator::passed()) {
            $_SESSION['alerts']['success'] [] = 'Registration successful!';

            (new RegistrationService())->execute($_POST['name'], $_POST['email'], password_hash($_POST['password'], PASSWORD_DEFAULT));
        }
        return new Redirect('/register');
    }
}