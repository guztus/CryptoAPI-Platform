<?php

namespace App\Services\User\Transaction;

use App\Models\Transaction;
use App\Repositories\User\Transaction\UserTransactionHistoryRepository;
use App\Repositories\User\UserAssetsRepository;
use App\TransactionIdGenerator;

class TransactionSendReceiveService
{
    public function execute(
        int    $userId,
        string $transactionType,
        string $symbol,
        float  $amount
    ): void
    {
        $transaction = new Transaction(
            TransactionIdGenerator::get(),
            $userId,
            $transactionType,
            $symbol,
            null,
            null,
            null,
            date('Y-m-d H:i:s')
        );

        (new UserTransactionHistoryRepository())
            ->add($transaction);

        (new UserAssetsRepository())
            ->modifyAssets(
                $userId,
                $transactionType,
                $symbol,
                $amount,
                null,
                null,
                null
            );
    }
}