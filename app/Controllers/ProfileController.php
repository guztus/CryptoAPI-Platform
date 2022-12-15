<?php declare(strict_types=1);

namespace App\Controllers;

use App\Models\Transaction;
use App\Redirect;
use App\Services\User\Transaction\UserDoTransactionService;
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

        (new UserDoTransactionService())->execute(
                $_SESSION['auth_id'],
                $_POST['transactionType'],
                '$',
                null,
                null,
                (float)$_POST['fiatAmount'],
        );

        return Redirect::to('/profile');
    }
}