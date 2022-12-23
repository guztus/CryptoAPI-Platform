<?php declare(strict_types=1);

namespace App\Controllers;

use App\Redirect;
use App\Repositories\Coins\CoinsRepository;
use App\Repositories\User\UserAssetsRepository;
use App\Services\CoinsService;
use App\Services\User\Transaction\TransactionBuySellService;
use App\Services\User\Transaction\TransactionShortCloseService;
use App\Services\User\UserAssetService;
use App\Template;

class CoinsController
{
    private CoinsRepository $coinsRepository;

    public function __construct(CoinsRepository $coinsRepository)
    {
        $this->coinsRepository = $coinsRepository;
    }

    public function index(array $vars): Template
    {
        if (!empty($vars['symbol'])) {
            $searchedCoin = (new CoinsService($this->coinsRepository))->execute(strtoupper($vars['symbol']));
            if ($searchedCoin) {
                $userAssetHoldings = (new UserAssetService)->show($_SESSION['auth_id'], $vars['symbol']);

                return Template::render('/main/singleCoin.view.twig', ['coin' => $searchedCoin, 'asset' => $userAssetHoldings]);
            }
        }
        $coinList = ((new CoinsService($this->coinsRepository))->execute())->getAllCoins();
        return Template::render('/main/coinList.view.twig', ['coins' => $coinList]);
    }

    public function buy(): Redirect
    {
        $transactionType = __FUNCTION__;
        return $this->standardTransaction($transactionType);
    }

    public function sell(): Redirect
    {
        $transactionType = __FUNCTION__;
        return $this->standardTransaction($transactionType);
    }

    public function short(): Redirect
    {
        $transactionType = __FUNCTION__;
        return $this->shortingTransaction($transactionType);
    }

    public function closeShort(): Redirect
    {
        $transactionType = __FUNCTION__;
        return $this->shortingTransaction($transactionType);
    }

    private function standardTransaction($transactionType): Redirect
    {
        if (!$_SESSION['auth_id']) {
            $_SESSION['errors']['transaction'] [] =
                'You must be logged in to do transactions!';
            return Redirect::to("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
        }

        (new TransactionBuySellService($this->coinsRepository))->execute(
            (int)$_SESSION['auth_id'],
            $transactionType,
            $_POST['symbol'],
            (float)$_POST['coinAmount'],
        );

        return Redirect::to("http://$_SERVER[HTTP_HOST]/crypto/" . $_POST['symbol']);
    }

    private function shortingTransaction($transactionType): Redirect
    {
        if (!$_SESSION['auth_id']) {
            $_SESSION['errors']['transaction'] [] =
                'You must be logged in to do transactions!';
            return Redirect::to("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
        }

        (new TransactionShortCloseService($this->coinsRepository))->execute(
            (int)$_SESSION['auth_id'],
            $transactionType,
            $_POST['symbol'],
            (float)$_POST['coinAmount'],
        );

        return Redirect::to("http://$_SERVER[HTTP_HOST]/crypto/" . $_POST['symbol']);
    }
}