<?php declare(strict_types=1);

namespace App\Controllers;

use App\Redirect;
use App\Services\User\UserService;
use App\Template;

class ProfileController
{
    public function showForm()
    {
        if (empty($_SESSION['auth_id'])) {
            return Redirect::to('/login');
        }
        return Template::render('profile/profile.view.twig');
    }

    public function update(): Redirect
    {
        if (empty($_SESSION['auth_id'])) {
            return Redirect::to('/login');
        }

        (new UserService())->modifyFiatBalance(
            (int)$_SESSION['auth_id'],
            (float)$_POST['fiatAmount'],
            $_POST['transactionType']
        );

        return Redirect::to('/profile');
    }
}