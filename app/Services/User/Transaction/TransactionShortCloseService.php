<?php

namespace App\Services\User\Transaction;

use App\Models\Transaction;
use App\Repositories\Coins\CoinsRepository;
use App\Repositories\User\Transaction\UserTransactionHistoryRepository;
use App\Repositories\User\UserAssetsRepository;
use App\Repositories\User\UserRepository;
use App\Services\CoinsService;
use App\Services\User\UserGetInformationService;
use App\TransactionIdGenerator;
use App\Validator;

class TransactionShortCloseService
{
    private CoinsRepository $coinsRepository;

    public function __construct(CoinsRepository $coinsRepository)
    {
        $this->coinsRepository = $coinsRepository;
    }

    public function execute(
        int    $userId,
        string $transactionType,
        string $symbol,
        float  $assetAmount
    )
    {
        $assetType = 'short';

        $currentCoinPrice = ((new CoinsService($this->coinsRepository))->execute($symbol))->getPrice();

        $currentUser = (new UserGetInformationService())->execute($userId);
        $userFiatBalance = $currentUser->getFiatBalance();

        $fiatAmount = 0;
        if ($transactionType == 'closeShort') {
            $userOwnedAsset = ((new UserAssetsRepository($this->coinsRepository))->getSingleAsset($userId, $symbol, $assetType));

            if ($userOwnedAsset) {
                $fiatAmount = (($userOwnedAsset->getAverageCost() - $currentCoinPrice) * $assetAmount);
            }
        }

        (new Validator())->transaction(
            $userId,
            $transactionType,
            $symbol,
            $assetType,
            $fiatAmount,
            $assetAmount,
            $userFiatBalance,
        );

        if (!Validator::passed()) {
            return;
        }

        // if all is ok, create transaction & modify assets
        $_SESSION['alerts']['transaction'] [] =
            ucfirst($transactionType) . " order successful: $assetAmount $symbol for " . number_format($fiatAmount, 2) . " USD!";

        if ($transactionType == 'short') {
            $fiatAmount = $fiatAmount * (-1);
        } else {
            $assetAmount = $assetAmount * (-1);
        }

        $transaction = new Transaction(
            TransactionIdGenerator::get(),
            $userId,
            $transactionType,
            $symbol,
            $assetAmount ?? null,
            $currentCoinPrice ?? null,
            $fiatAmount,
            date('Y-m-d H:i:s')
        );

        (new UserTransactionHistoryRepository())
            ->add($transaction);

        $oldDollarCostAverage = (new UserAssetsRepository())->getOldDollarCostAverage($userId, $symbol, $assetType);
        $purchaseDollarCostAverage = $currentCoinPrice;

        (new UserAssetsRepository())
            ->modifyAssets(
                $transaction->getUserId(),
                $transaction->getTransactionType(),
                $transaction->getSymbol(),
                $assetType,
                $transaction->getAmount(),
                $transaction->getPrice(),
                $oldDollarCostAverage,
                $purchaseDollarCostAverage
            );

        (new UserRepository())
            ->modifyFiatBalance(
                $transaction->getUserId(),
                $transaction->getOrderSum(),
            );
    }
}