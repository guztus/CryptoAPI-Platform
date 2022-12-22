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

    public function execute(int $userId): array
    {
        $displayData = [];

        $assetList = (new UserAssetsRepository($this->coinsRepository))
            ->getAssetList($userId);

        $transactionHistory = (new UserTransactionHistoryRepository())
            ->getHistory($userId);

        if (!empty($assetList)) {
            $displayData ['assetList'] = $assetList->getAllAssets() ?? [];
        }
        $displayData ['transactionHistory'] = $transactionHistory->all() ?? [];

        return $displayData;
    }
}