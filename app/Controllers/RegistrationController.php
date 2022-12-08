<?php declare(strict_types=1);

namespace App\Controllers;

use App\Template;

class RegistrationController
{
    public function index(): Template
    {
        return new Template('/registration/registration.view.twig');
    }
}