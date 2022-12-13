<?php declare(strict_types=1);

namespace App\Services\User;

use App\Models\Collections\TransactionCollection;
use App\Models\Transaction;
use App\Repositories\User\UserTransactionHistoryRepository;

class UserTransactionHistoryService
{
    public function getTransactionHistory(int $userId): TransactionCollection
    {
        return (new UserTransactionHistoryRepository())
            ->getTransactionHistory($userId);
    }

    public function addTransaction(Transaction $transaction): void
    {
        (new UserTransactionHistoryRepository())
            ->addTransaction($transaction);
    }
}
