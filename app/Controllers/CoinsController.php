<?php declare(strict_types=1);

namespace App\Controllers;

use App\Services\CoinsService;
use App\Template;
use App\Validator;

class CoinsController
{
    public function index(): Template
    {
        if (!empty($_GET['search'])) {
            $searchedCoin = (new CoinsService())->execute(strtoupper($_GET['search']));
            if ($searchedCoin) {
//                $searchedCoin->getAmount();
                return Template::render('/main/singleCoin.view.twig', ['coin' => $searchedCoin]);
            }
//            $_SESSION['errors']['search'] [] = 'There is no coin with such symbol'; // cant use this because there is no REDIRECT
        }
        $coinList = ((new CoinsService())->execute())->getAllCoins();
        return Template::render('/main/coinList.view.twig', ['coins' => $coinList]);
    }

    public function doTransaction()
    {
        (new Validator())->transactionOrder($_SESSION['auth_id'], $_POST['symbol'], $_POST['transactionType'], $_POST['fiatAmount']);
    }
}