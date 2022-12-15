<?php declare(strict_types=1);

namespace App\Services\User\Transaction;

use App\Models\Transaction;
use App\Repositories\User\Transaction\UserTransactionHistoryRepository;
use App\Repositories\User\UserAssetsRepository;
use App\Repositories\User\UserRepository;
use App\TransactionIdGenerator;

class UserDoTransactionService
{
    public function execute(
        int    $userId,
        string $transactionType,
        string $symbol,
        ?float $amount,
        ?float $price,
        ?float $fiatAmount
        // AMOUNT null when DEPOSIT / WITHDRAW
        // PRICE & FIAT AMOUNT null when SEND
    ): void
    {
        $transaction = new Transaction(
            TransactionIdGenerator::get(),
            $userId,
            $transactionType,
            $symbol,
            $amount,
            $price,
            $fiatAmount,
            date('Y-m-d H:i:s')
        );

        (new UserTransactionHistoryRepository())
            ->addTransaction($transaction);

//        if transaction type is buy or sell, we need to update user's ASSETS and BALANCE
        if (
            $transactionType == 'buy' ||
            $transactionType == 'sell'
        ) {

            $purchaseDollarCostAverage = $fiatAmount / $amount;
            $oldDollarCostAverage = (new UserAssetsRepository())->getOldDollarCostAverage($userId, $symbol);

            (new UserAssetsRepository())
                ->modifyAssets(
                    $userId,
                    $symbol,
                    $amount,
                    $transactionType,
                    $price,
                    $oldDollarCostAverage,
                    $purchaseDollarCostAverage
                );
            (new UserRepository())
                ->modifyFiatBalance(
                    $transaction->getUserId(),
                    $transaction->getOrderSum(),
                    $transaction->getTransactionType()
                );
        } //        if transaction type is deposit or withdraw, we need to update user's BALANCE
        else if (
            $transactionType == 'deposit' ||
            $transactionType == 'withdraw'
        ) {
            (new UserRepository())
                ->modifyFiatBalance(
                    $transaction->getUserId(),
                    $transaction->getOrderSum(),
                    $transaction->getTransactionType()
                );
        } //        if transaction type is send we need to update users ASSETS
        else if (
            $transactionType == 'send'
        ) {
            die;
//            (new UserAssetsRepository())
//                ->modifyAssets(
//                    $userId,
//                    $symbol,
//                    $amount,
//                    $transactionType
//                );
        }
    }
}
