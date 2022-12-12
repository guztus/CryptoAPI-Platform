<?php

namespace App\Repositories\User;

use App\Database;
use App\Models\Transaction;
use Doctrine\DBAL\Query\QueryBuilder;

class UserTransactionHistoryRepository
{
    private Database $database;
    private QueryBuilder $queryBuilder;

    public function __construct()
    {
        $this->database = (new Database());
        $this->queryBuilder = $this->database->getConnection()->createQueryBuilder();
    }

    public function getTransactionHistory(int $userId): array
    {
        $queryBuilder = $this->queryBuilder;

        $queryBuilder
            ->select('*')
            ->from('transaction_history')
            ->where("user_id = ?")
            ->setParameter(0, $userId)
            ->orderBy('timestamp', 'DESC');

        $result = $queryBuilder->executeQuery()->fetchAllAssociative();

        return $result;
    }

    public function addTransaction(Transaction $transaction): void
    {
        $queryBuilder = $this->queryBuilder;

        $queryBuilder
            ->insert('transaction_history')
            ->values([
                'user_id' => '?',
                'transaction_type' => '?',
                'symbol' => '?',
                'amount' => '?',
                'price' => '?',
                'order_sum' => '?',
                'timestamp' => '?'
            ])
            ->setParameter(0, $transaction->getUserId())
            ->setParameter(1, $transaction->getTransactionType())
            ->setParameter(2, $transaction->getSymbol())
            ->setParameter(3, $transaction->getAmount())
            ->setParameter(4, $transaction->getPrice())
            ->setParameter(5, $transaction->getOrderSum())
            ->setParameter(6, $transaction->getTimestamp());

        $queryBuilder->executeQuery();
    }
}