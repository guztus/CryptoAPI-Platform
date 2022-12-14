<?php

namespace App\Repositories\User\Transaction;

use App\Database;
use App\Models\Collections\TransactionCollection;
use App\Models\Transaction;
use Doctrine\DBAL\Connection;

class UserTransactionHistoryRepository
{
    private ?Connection $connection;

    public function __construct()
    {
        $this->connection = (new Database())->getConnection();
    }

    public function getHistory(int $userId): TransactionCollection
    {
        $queryBuilder = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('transaction_history')
            ->where("user_id = ?")
            ->setParameter(0, $userId)
            ->orderBy('timestamp', 'DESC');

        $transactionList = $queryBuilder->executeQuery()->fetchAllAssociative();

        $transactionCollection = new TransactionCollection();

        foreach ($transactionList as $transaction) {
            $transactionCollection->addTransaction(new Transaction(
                $transaction['extra_unique_id'],
                $transaction['user_id'],
                $transaction['type'],
                $transaction['symbol'],
                $transaction['amount'],
                $transaction['price'],
                $transaction['order_sum'],
                $transaction['timestamp']
            ));
        }

        return $transactionCollection;
    }

    public function add(Transaction $transaction): void
    {
        $queryBuilder = $this->connection->createQueryBuilder()
            ->insert('transaction_history')
            ->values([
                'extra_unique_id' => '?',
                'user_id' => '?',
                'type' => '?',
                'symbol' => '?',
                'amount' => '?',
                'price' => '?',
                'order_sum' => '?',
                'timestamp' => '?'
            ])
            ->setParameter(0, $transaction->getId())
            ->setParameter(1, $transaction->getUserId())
            ->setParameter(2, $transaction->getTransactionType())
            ->setParameter(3, $transaction->getSymbol())
            ->setParameter(4, $transaction->getAmount())
            ->setParameter(5, $transaction->getPrice())
            ->setParameter(6, $transaction->getOrderSum())
            ->setParameter(7, $transaction->getTimestamp());

        $queryBuilder->executeQuery();
    }
}