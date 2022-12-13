<?php declare(strict_types=1);

namespace App\Models;

class Transaction
{
    private ?int $id;
    private int $userId;
    private string $transactionType;
    private string $symbol;
    private ?float $amount;
    private ?float $price;
    private float $orderSum;
    private string $timeStamp;

    public function __construct(
        ?int   $id,
        int    $userId,
        string $transactionType,
        string $symbol,
        ?float $amount,
        ?float $price,
        float  $orderSum,
        string $timeStamp)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->transactionType = $transactionType;
        $this->symbol = $symbol;
        $this->amount = $amount;
        $this->price = $price;
        $this->orderSum = $orderSum;
        $this->timeStamp = $timeStamp;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getTransactionType(): string
    {
        return $this->transactionType;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function getOrderSum(): float
    {
        return $this->orderSum;
    }

    public function getTimeStamp(): string
    {
        return $this->timeStamp;
    }
}