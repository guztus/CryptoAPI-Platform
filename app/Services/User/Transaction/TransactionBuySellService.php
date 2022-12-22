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

class TransactionBuySellService
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
        if ($transactionType == 'close') {
            $transactionType = 'closeShort';
            $assetType = 'short';
        } else if ($transactionType == 'short') {
            $assetType = 'short';
        }

        $currentCoinPrice = ((new CoinsService($this->coinsRepository))->execute($symbol))->getPrice();

        $userAssetsRepository = ((new UserAssetsRepository($this->coinsRepository))->getSingleAsset($userId, $symbol));

        $currentUser = (new UserGetInformationService())->execute($userId);
        $userFiatBalance = $currentUser->getFiatBalance();

        if ($transactionType == 'closeShort') {
            $userAssetsRepository = ((new UserAssetsRepository($this->coinsRepository))->getSingleAsset($userId, $symbol, $assetType ?? null));

            if (!$userAssetsRepository) {
                $fiatAmount = 0;
            } else {

                $fiatAmount = (($userAssetsRepository->getAverageCost() - $currentCoinPrice) * $userAssetsRepository->getAmount());
            }

        } else if ($transactionType == 'short') {
            $fiatAmount = 0;
        } else {
            $fiatAmount = $assetAmount * $currentCoinPrice;
        }

        (new Validator())->buySellTransaction(
            $userId,
            $transactionType,
            $symbol,
            $assetType ?? null,
            $fiatAmount,
            $assetAmount,
            $userFiatBalance,
        );

        // if didn't pass Validation, errors will be from in Validator class
        if (!Validator::passed()) {
            return;
        }

        // if all is ok, create transaction & modify assets
        $_SESSION['alerts']['transaction'] [] =
            ucfirst($transactionType) . " order successful: $assetAmount $symbol for $fiatAmount USD!";

//        var_dump($_POST);die;
        if ($transactionType == 'buy' || $transactionType == 'short') {
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

        if ($transactionType == 'short' || $transactionType == 'closeShort') {
            $type = 'short';
        } else {
            $type = 'standard';
        }

        $oldDollarCostAverage = (new UserAssetsRepository())->getOldDollarCostAverage($userId, $symbol, $type);

        $purchaseDollarCostAverage = $currentCoinPrice;

        (new UserAssetsRepository())
            ->modifyAssets(
                $transaction->getUserId(),
                $transaction->getTransactionType(),
                $transaction->getSymbol(),
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