<?php

namespace App\Models;

class Transaction
{
    private int $userId;
    private string $transactionType;
    private string $symbol;
    private ?float $amount;
    private ?float $price;
    private float $orderSum;
    private string $timeStamp;

    public function __construct(
        int    $userId,
        string $transactionType,
        string $symbol,
        ?float  $amount,
        ?float  $price,
        float  $orderSum,
        string $timeStamp)
    {
        $this->userId = $userId;
        $this->transactionType = $transactionType;
        $this->symbol = $symbol;
        $this->amount = $amount;
        $this->price = $price;
        $this->orderSum = $orderSum;
        $this->timeStamp = $timeStamp;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getTransactionType(): string
    {
        return $this->transactionType;
    }

    public function getSymbol()
    {
        return $this->symbol;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getOrderSum()
    {
        return $this->orderSum;
    }

    public function getTimeStamp(): string
    {
        return $this->timeStamp;
    }
}