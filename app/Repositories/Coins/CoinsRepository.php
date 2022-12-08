<?php declare(strict_types=1);

namespace App\Repositories\Coins;

use App\Models\Collections\CoinCollection;

interface CoinsRepository
{
    public function getList(): CoinCollection;
}