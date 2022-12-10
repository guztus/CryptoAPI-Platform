<?php declare(strict_types=1);

namespace App\Controllers;

use App\Services\CoinsService;
use App\Template;

class CoinsController
{
    public function index(): Template
    {
        if (!empty($_GET['search'])) {
            $searchedCoin = ((new CoinsService())->execute($_GET['search']));
            return Template::render('/main/singleCoin.view.twig', ['coin' => $searchedCoin]);
        } else {
            $coinList = ((new CoinsService())->execute())->getAllCoins();
            return Template::render('/main/coinList.view.twig', ['coins' => $coinList]);
        }
    }

    public function doTransaction()
    {
//        var_dump($_POST);
        // would have to get the REAL CURRENT price of the coin
//        $price = get info about the SYMBOL from the API
//        var_dump($_POST['buy']/$price); // this is the amount of coins bought (User spent $$$ on coins)
        // Then we would need to subtract from the user's FIAT BALANCE the amount of coins bought * the current price of the coin
        // Then we would need to add to the user's CRYPTO BALANCE the amount of coins bought & update the prices there on every reload ...
        // Then we would need to add to the user's TRANSACTION HISTORY the amount of coins bought & the current price of the coin

        // ... for SOLD Then we would need to add to the user's TRANSACTION HISTORY the amount of coins sold & the current price of the coin


//        var_dump($_POST['sell']/$price); // this is the amount of coins sold (User sold coins for $$$)

//        $transaction = (new CoinsService())->execute($_POST['coin'], $_POST['amount'], $_POST['type']);
//        return $transaction;
    }
}