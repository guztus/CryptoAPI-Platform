<?php

namespace App\Services;

use App\Repositories\Coins\CoinsRepository;
use App\Repositories\User\Transaction\UserTransactionHistoryRepository;
use App\Repositories\User\UserAssetsRepository;

class PortfolioService
{
    private CoinsRepository $coinsRepository;

    public function __construct(CoinsRepository $coinsRepository)
    {
        $this->coinsRepository = $coinsRepository;
    }

    public function execute(): array
    {
        $displayData = [];

        $assetList = (new UserAssetsRepository($this->coinsRepository))
            ->getAssetList($_SESSION['auth_id']);

        $transactionHistory = (new UserTransactionHistoryRepository())
            ->getHistory($_SESSION['auth_id']);

//        $shortPositions = (new UserShortPositionsRepository())
//            ->getShortPositions($_SESSION['auth_id']);

        if (!empty($assetList)) {
            $displayData ['assetList'] = $assetList->getAllAssets() ?? [];
        }
        $displayData ['transactionHistory'] = $transactionHistory->all() ?? [];
//        $displayData ['shortPositions'] = $transactionHistory->all() ?? [];

        return $displayData;
    }
}