<?php declare(strict_types=1);

namespace App\Controllers;

use App\Redirect;
use App\Services\User\Transaction\TransactionDepositWithdrawService;
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

//        $user->withdraw()
//            (int)$_SESSION['auth_id'],
//            $_POST['transactionType'],
//            '$',
//            (float)$_POST['fiatAmount'],
//        );
//
//        	$user->withdrawBalance
//        	$user->modifyAs...

//            (`$this->userRepository->save($user)`) db – save($user) ..


        (new TransactionDepositWithdrawService())->execute(
            (int)$_SESSION['auth_id'],
            $_POST['transactionType'],
            '$',
            (float)$_POST['fiatAmount'],
        );

        return Redirect::to('/profile');
    }
}