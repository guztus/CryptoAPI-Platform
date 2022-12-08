<?php

namespace App\Models\Collections;

use App\Models\Coin;

class CoinCollection
{
    private array $coins = [];

    public function addCoins(Coin ...$coins)
    {
        $this->coins = array_merge($this->coins, $coins);
    }

    public function getCoins(): array
    {
        return $this->coins;
    }
}