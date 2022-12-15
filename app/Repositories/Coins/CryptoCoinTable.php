<?php declare(strict_types=1);

namespace App\Repositories\Coins;

use App\Database;
use App\Models\Coin;
use App\Models\Collections\CoinCollection;

class CryptoCoinTable implements CoinsRepository
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
        foreach ($results as $coin) {
            $coinList->addCoin(
                new Coin(
                    (int)$coin['id'],
                    $coin['name'],
                    $coin['symbol'],
                    $coin['date_added'],
                    (float)$coin['max_supply'],
                    (float)$coin['circulating_supply'],
                    (float)$coin['total_supply'],
                    $coin['last_updated'],
                    (float)$coin['price'],
                    (float)$coin['volume_24h'],
                    (float)$coin['volume_change_24h'],
                    (float)$coin['percent_change_24h'],
                    (float)$coin['market_cap'],
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

        if (!$coin) {
            return null;
        }

        return new Coin(
            (int)$coin['id'],
            $coin['name'],
            $coin['symbol'],
            $coin['date_added'],
            (float)$coin['max_supply'],
            (float)$coin['circulating_supply'],
            (float)$coin['total_supply'],
            $coin['last_updated'],
            (float)$coin['price'],
            (float)$coin['volume_24h'],
            (float)$coin['volume_change_24h'],
            (float)$coin['percent_change_24h'],
            (float)$coin['market_cap'],
        );
    }
}