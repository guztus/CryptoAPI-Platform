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
        float  $fiatAmount
    )
    {
        $currentCoinPrice = ((new CoinsService($this->coinsRepository))->execute($symbol))->getPrice();
        $currentUser = (new UserGetInformationService())->execute($userId);
        $userFiatBalance = $currentUser->getFiatBalance();
        $assetAmount = $fiatAmount / $currentCoinPrice;

        (new Validator())->buySellTransaction(
            $userId,
            $transactionType,
            $symbol,
            $fiatAmount,
            $currentCoinPrice,
            $userFiatBalance,
        );

        // if didn't pass Validation, errors will be from in Validator class
        if (!Validator::passed()) {
            return;
        }
        // if all is ok, create transaction & modify assets
        $_SESSION['alerts']['transaction'] [] =
            ucfirst($transactionType) . " order successful: $assetAmount $symbol for $fiatAmount USD!";

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

        $oldDollarCostAverage = (new UserAssetsRepository())->getOldDollarCostAverage($userId, $symbol);
        $purchaseDollarCostAverage = $fiatAmount / $assetAmount;


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
                $transaction->getTransactionType()
            );
    }
}