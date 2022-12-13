<?php

namespace App\Repositories\User;

use App\Database;
use App\Models\Collections\TransactionCollection;
use App\Models\Transaction;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

class UserTransactionHistoryRepository
{
    private ?Connection $connection;
    private QueryBuilder $queryBuilder;

    public function __construct()
    {
        $this->connection = (new Database())->getConnection();
        $this->queryBuilder = $this->connection->createQueryBuilder();
    }

    public function getTransactionHistory(int $userId): TransactionCollection
    {
        $queryBuilder = $this->queryBuilder;

        $queryBuilder
            ->select('*')
            ->from('transaction_history')
            ->where("user_id = ?")
            ->setParameter(0, $userId)
            ->orderBy('timestamp', 'DESC');

        $transactionList = $queryBuilder->executeQuery()->fetchAllAssociative();

        $transactionCollection = new TransactionCollection();

        foreach ($transactionList as $transaction) {
            $transactionCollection->addTransaction(new Transaction(
                $transaction['id'],
                $transaction['user_id'],
                $transaction['transaction_type'],
                $transaction['symbol'],
                $transaction['amount'],
                $transaction['price'],
                $transaction['order_sum'],
                $transaction['timestamp']
            ));
        }

        return $transactionCollection;
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