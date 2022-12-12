<?php declare(strict_types=1);

namespace App\Controllers;

use App\Redirect;
use App\Services\User\UserService;
use App\Template;
use App\Validator;

class LoginController
{
    public function showForm(): Template
    {
        return new Template('/login/login.view.twig');
    }

    public function login(): Redirect
    {
        $validator = new Validator();
        $validator->login($_POST['email'], $_POST['password']);

        if (Validator::passed()) {
            $currentUser = (new UserService())->getUserData(null, $_POST['email']);
            $_SESSION['alerts']['success'] [] = 'Login successful!';

            $_SESSION['auth_id'] = $currentUser->getId();
            return Redirect::to('/');
        }
        return Redirect::to('/login');
    }
}