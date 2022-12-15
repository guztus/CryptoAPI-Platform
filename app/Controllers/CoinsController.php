<?php declare(strict_types=1);

namespace App\Controllers;

use App\Redirect;
use App\Repositories\Coins\CoinsRepository;
use App\Services\CoinsService;
use App\Services\User\Transaction\UserDoTransactionService;
use App\Template;
use App\Validator;

class CoinsController
{
    private CoinsRepository $coinsRepository;

    public function __construct(CoinsRepository $coinsRepository)
    {
        $this->coinsRepository = $coinsRepository;
    }

    public function index(): Template
    {
        if (!empty($_GET['search'])) {
            $searchedCoin = (new CoinsService($this->coinsRepository))->execute(strtoupper($_GET['search']));
            if ($searchedCoin) {
                return Template::render('/main/singleCoin.view.twig', ['coin' => $searchedCoin]);
            }
//            $_SESSION['errors']['search'] [] =
//                'There is no coin with such symbol'; // cant use this because there is no REDIRECT
        }
        $coinList = ((new CoinsService($this->coinsRepository))->execute())->getAllCoins();
        return Template::render('/main/coinList.view.twig', ['coins' => $coinList]);
    }

    public function doTransaction(): Redirect
    {
        if (!$_SESSION['auth_id']) {
            $_SESSION['errors']['transaction'] [] =
                'You must be logged in to do transactions!';
            return Redirect::to("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
        }

        (new UserDoTransactionService($this->coinsRepository))->execute(
            (int)$_SESSION['auth_id'],
            $_POST['transactionType'],
            $_POST['symbol'],
            (float)$_POST['fiatAmount'],
            null
        );

        return Redirect::to("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
//        return (new Validator($this->coinsRepository))->transactionOrder(
//            $_SESSION['auth_id'],
//            $_POST['symbol'],
//            $_POST['transactionType'],
//            $_POST['fiatAmount']
//        );
    }
}