<?php

namespace App\Repositories\Coins;

use App\Database;
use App\Models\Coin;
use App\Models\Collections\CoinCollection;

class CryptoAPIPlatformCryptoCoinTable implements CoinsRepository
{
    public function getList(): CoinCollection
    {
        $conn = (new Database())->getConnection();
        $queryBuilder = $conn->createQueryBuilder();

        $queryBuilder
            ->select('*')
            ->from('crypto_coins');

        $results = $queryBuilder->executeQuery()->fetchAllAssociative();

        $coinList = new CoinCollection();
        foreach($results as $coin) {
            $coinList->addCoins(
                new Coin(
                    $coin['id'],
                    $coin['name'],
                    $coin['symbol'],
                    $coin['date_added'],
                    $coin['max_supply'],
                    $coin['circulating_supply'],
                    $coin['total_supply'],
                    $coin['last_updated'],
                    $coin['price'],
                    $coin['volume_24h'],
                    $coin['volume_change_24h'],
                    $coin['percent_change_24h'],
                    $coin['market_cap'],
            ));
        }

        return $coinList;
    }

    public function getBySymbol(string $symbol): ?Coin
    {
        $conn = (new Database())->getConnection();
        $queryBuilder = $conn->createQueryBuilder();

        $queryBuilder
            ->select('*')
            ->from('crypto_coins')
            ->where('symbol = ?')
            ->setParameter(0, $symbol);

        $coin = $queryBuilder->executeQuery()->fetchAssociative();

        return new Coin(
            $coin['id'],
            $coin['name'],
            $coin['symbol'],
            $coin['date_added'],
            $coin['max_supply'],
            $coin['circulating_supply'],
            $coin['total_supply'],
            $coin['last_updated'],
            $coin['price'],
            $coin['volume_24h'],
            $coin['volume_change_24h'],
            $coin['percent_change_24h'],
            $coin['market_cap'],
        );
    }
}