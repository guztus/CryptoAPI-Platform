<?php

namespace App\Services\User;

use App\Models\Transaction;
use App\Repositories\User\UserTransactionHistoryRepository;

class UserTransactionHistoryService
{
    public function getTransactionHistory(int $userId): array
    {
        return (new UserTransactionHistoryRepository())->getTransactionHistory($userId);
    }

    public function addTransaction(Transaction $transaction): void
    {
        (new UserTransactionHistoryRepository())->addTransaction($transaction);
    }
}
