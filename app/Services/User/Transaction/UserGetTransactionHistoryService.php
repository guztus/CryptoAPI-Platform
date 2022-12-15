<?php

namespace App\Services\User\Transaction;

use App\Models\Collections\TransactionCollection;
use App\Repositories\User\Transaction\UserTransactionHistoryRepository;

class UserGetTransactionHistoryService
{
    public function execute(int $userId): TransactionCollection
    {
        return (new UserTransactionHistoryRepository())
            ->getTransactionHistory($userId);
    }
}