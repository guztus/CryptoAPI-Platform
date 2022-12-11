<?php declare(strict_types=1);

namespace App\Repositories\Coins;

use App\Models\Coin;
use App\Models\Collections\CoinCollection;

interface CoinsRepository
{
    public function getList(): CoinCollection;
    public function getBySymbol(string $symbol): ?Coin;
}