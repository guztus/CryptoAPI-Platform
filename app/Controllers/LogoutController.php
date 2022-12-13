<?php declare(strict_types=1);

namespace App\Controllers;

use App\Redirect;

class LogoutController
{
    public function logout(): Redirect
    {
        unset($_SESSION['auth_id']);
        return Redirect::to('/');
    }
}