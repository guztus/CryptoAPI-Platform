<?php

namespace App\Controllers;

use App\Redirect;
use App\Repositories\Coins\CoinsRepository;
use App\Services\User\Assets\UserAssetsListService;
use App\Services\User\Transaction\UserDoTransactionService;
use App\Services\User\UserGetInformationService;
use App\Template;

class ViewProfileController
{
    private CoinsRepository $coinsRepository;

    public function __construct(CoinsRepository $coinsRepository)
    {
        $this->coinsRepository = $coinsRepository;
    }

    public function show(array $vars)
    {
//      get user id from url
//      get user from db
//      get basic data about the user

        if (!empty($vars['id'])) {
            $user = (new UserGetInformationService())->execute($vars['id']);

            if (!$user) {
                return Redirect::to('/');
            }
        }
//
        if (!$_SESSION['auth_id']) {
            $assetList = null;
        } else {
            $assetList = ((new UserAssetsListService($this->coinsRepository))->execute($_SESSION['auth_id']));
        }

        if (empty($assetList)) {
            $assetList = null;
        } else {
            $assetList = $assetList->getAllAssets();
        }

        return Template::render('profile/viewProfile.view.html.twig', [
            'user' => $user,
            'assetList' => $assetList ?? []
        ]);
    }

    public function sendCoins(): Redirect
    {
//        validate if user has enough coins
//        validate if user exists
//        validate if user is not the same as the logged-in user
//        send coins
//        redirect to profile

        (new UserDoTransactionService($this->coinsRepository))->execute(
            $_SESSION['auth_id'],
            'send',
            $_POST['symbol'],
            null,
            $_POST['coinAmount'],
        );

        (new UserDoTransactionService($this->coinsRepository))->execute(
            $_POST['receivingUserId'],
            'receive',
            $_POST['symbol'],
            null,
            $_POST['coinAmount'],
        );

        return Redirect::to("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
    }
}