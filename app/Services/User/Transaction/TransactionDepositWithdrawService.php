<?php

namespace App\Services\User\Transaction;

use App\Models\Transaction;
use App\Repositories\User\Transaction\UserTransactionHistoryRepository;
use App\Repositories\User\UserRepository;
use App\TransactionIdGenerator;

class TransactionDepositWithdrawService
{
    public function execute(
        $userId,
        $transactionType,
        $symbol,
        $fiatAmount
    ): void
    {
        if ($transactionType == 'withdraw') {
            $fiatAmount = $fiatAmount * (-1);
        }

        $transaction = new Transaction(
            TransactionIdGenerator::get(),
            $userId,
            $transactionType,
            $symbol,
            null,
            null,
            $fiatAmount,
            date('Y-m-d H:i:s')
        );

        (new UserTransactionHistoryRepository())
            ->add($transaction);

        (new UserRepository())
            ->modifyFiatBalance(
                $transaction->getUserId(),
                $transaction->getOrderSum(),
                $transaction->getTransactionType()
            );
    }
}