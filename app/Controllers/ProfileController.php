<?php declare(strict_types=1);

namespace App\Controllers;

use App\Redirect;
use App\Repositories\Coins\CoinsRepository;
use App\Services\User\Transaction\UserDoTransactionService;
use App\Template;

class ProfileController
{
    private CoinsRepository $coinsRepository;

    public function __construct(CoinsRepository $coinsRepository)
    {
        $this->coinsRepository = $coinsRepository;
    }

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

        (new UserDoTransactionService($this->coinsRepository))->execute(
            (int)$_SESSION['auth_id'],
            $_POST['transactionType'],
            '$',
            (float)$_POST['fiatAmount'],
            null,
        );

        return Redirect::to('/profile');
    }
}