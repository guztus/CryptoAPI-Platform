<?php declare(strict_types=1);

namespace App\Repositories\Coins;

use App\Models\Coin;
use App\Models\Collections\CoinCollection;

class OtherServiceCryptoCoinsRepository implements CoinsRepository
{
    public function getList(): CoinCollection
    {
        $list = (new CoinCollection());
        $list->addCoin(new Coin(1, 'nonExistentCoin', 'NeC1', '1', 1, 1, 1, 1, 2, 0.123, 3, 3, 3, 1));
        $list->addCoin(new Coin(2, 'nonExistentCoin2', 'NeC2', '1', 1, 1, 1, 3, 2, 123123, 3, 3, 1000, 2));
        $list->addCoin(new Coin(3, 'nonExistentCoin3', 'NeC3', '1', 1, 1, 1, 2, 2, 1.2, 3, 3, -3, 3));

        return $list;
    }

    public function getBySymbol(string $symbol): Coin
    {
        return new Coin(3, 'nonExistentCoin3', 'NeC3', '1', 1234234234, 1234234234, 12342342344244, 2, 2, 2.4, 3234234234, 32, -52, 1231231233);
    }
}