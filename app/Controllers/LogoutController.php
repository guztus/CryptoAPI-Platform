<?php declare(strict_types=1);

namespace App\Controllers;

use App\Redirect;

class LogoutController
{
    public function logout(): Redirect
    {
        session_destroy();
        return Redirect::to('/');
    }
}