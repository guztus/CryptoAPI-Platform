<?php declare(strict_types=1);

namespace App\Models;

class Asset
{
    private string $symbol;
    private float $amount;
    private float $average_cost;
    private ?float $current_price;

    public function __construct(
        string $symbol,
        float $amount,
        float $average_cost,
        float $current_price = null)
    {
        $this->symbol = $symbol;
        $this->amount = $amount;
        $this->average_cost = $average_cost;
        $this->current_price = $current_price;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getAverageCost(): float
    {
        return $this->average_cost;
    }

    public function getCurrentPrice(): ?float
    {
        return $this->current_price;
    }

    public function setCurrentPrice($current_price): void
    {
        $this->current_price = $current_price;
    }
}