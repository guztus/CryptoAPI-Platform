<?php

namespace App\Controllers;

use App\Redirect;
use App\Repositories\Coins\CoinsRepository;
use App\Repositories\User\UserAssetsRepository;
use App\Services\User\Transaction\TransactionSendReceiveService;
use App\Services\User\UserGetInformationService;
use App\Template;
use App\Validator;

class ViewProfileController
{
    private CoinsRepository $coinsRepository;

    public function __construct(CoinsRepository $coinsRepository)
    {
        $this->coinsRepository = $coinsRepository;
    }

    public function searchUser(): Template
    {
        return Template::render('profile/searchUser.view.twig');
    }

    public function show(array $vars)
    {
        if (!empty($vars['id'])) {
            $user = (new UserGetInformationService())->execute($vars['id']);

            if (!$user) {
                $_SESSION['errors'] [] = 'User not found';
                return Redirect::to('/profile/');
            }
        }

        if (!$_SESSION['auth_id']) {
            $assetList = null;
        } else {
            $assetList = (new UserAssetsRepository($this->coinsRepository))
                ->getAssetList($_SESSION['auth_id']);
            if (empty($assetList)) {
                $assetList = null;
            } else {
                $assetList = $assetList->getAllAssets();
            }
        }

        return Template::render('profile/viewProfile.view.html.twig', [
            'user' => $user,
            'assetList' => $assetList ?? []
        ]);
    }

    public function getUser(): Redirect
    {
        if ($_POST['id']) {
            return Redirect::to("http://$_SERVER[HTTP_HOST]/profile/" . $_POST['id']);
        }

        return Redirect::to('/profile/');
    }

    public function sendCoins($vars): Redirect
    {
        $validator = new Validator();
        $validator->transactionValue($_POST['coinAmount']);
        $validator->checkPasswordById($_SESSION['auth_id'], $_POST['password']);
        $validator->assetAmount($_SESSION['auth_id'], $_POST['symbol'], $_POST['coinAmount']);

        if (!Validator::passed()) {
            return Redirect::to('/profile/' . $vars['id']);
        }

        $_SESSION['alerts']['success'] [] =
            "Successfully sent {$_POST['coinAmount']} {$_POST['symbol']} to user #{$_POST['receivingUserId']}!";

        (new TransactionSendReceiveService())->execute(
            $_SESSION['auth_id'],
            $_POST['receivingUserId'],
            'send',
            $_POST['symbol'],
            $_POST['coinAmount'],
        );

        (new TransactionSendReceiveService())->execute(
            $_POST['receivingUserId'],
            $_SESSION['auth_id'],
            'receive',
            $_POST['symbol'],
            $_POST['coinAmount'],
        );

        return Redirect::to("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
    }
}