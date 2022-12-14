<?php declare(strict_types=1);

namespace App\Models\Collections;

use App\Models\Transaction;

class TransactionCollection
{
    private array $transactions = [];

    public function __construct(array $transactions = [])
    {
        foreach ($transactions as $transaction) {
            $this->addTransaction($transaction);
        }
    }

    public function addTransaction (Transaction $transaction): void
    {
        $this->transactions [] = $transaction;
    }

    public function all(): array
    {
        return $this->transactions;
    }
}