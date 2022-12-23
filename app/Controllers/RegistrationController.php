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
        return Template::render('/registration/registration.view.twig');
    }

    public function register(): Redirect
    {
        (new RegistrationService())->execute(
            $_POST['name'],
            $_POST['email'],
            password_hash($_POST['password'], PASSWORD_DEFAULT)
        );

        return Redirect::to('/register');
    }
}