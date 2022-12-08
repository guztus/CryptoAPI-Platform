<?php declare(strict_types=1);

namespace App\Controllers;

use App\Template;

class LoginController
{
    public function index(): Template
    {
        return new Template('/login/login.view.twig');
    }
}