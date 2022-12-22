<?php declare(strict_types=1);

namespace App\Controllers;

use App\Redirect;
use App\Services\User\Transaction\TransactionDepositWithdrawService;
use App\Template;

class ProfileController
{
    public function show()
    {
        if (empty($_SESSION['auth_id'])) {
            return Redirect::to('/login');
        }
        return Template::render('profile/profile.view.twig');
    }

    public function updateBalance(): Redirect
    {
        if (empty($_SESSION['auth_id'])) {
            return Redirect::to('/login');
        }

        (new TransactionDepositWithdrawService())->execute(
            (int)$_SESSION['auth_id'],
            $_POST['transactionType'],
            '$',
            (float)$_POST['fiatAmount'],
        );

        return Redirect::to('/profile');
    }
}