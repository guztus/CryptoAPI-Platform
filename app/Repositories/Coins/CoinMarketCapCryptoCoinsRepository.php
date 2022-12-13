<?php declare(strict_types=1);

namespace App\Repositories\Coins;

use App\CoinMarketCapCryptoAPI;
use App\Models\Coin;
use App\Models\Collections\CoinCollection;

class CoinMarketCapCryptoCoinsRepository implements CoinsRepository
{
    public function getList(): CoinCollection
    {
        $results = (new CoinMarketCapCryptoAPI())->getList();
        $coins = new CoinCollection();

        foreach ($results->data as $coin) {
            $coins->addCoin(
                new Coin(
                    $coin->id,
                    $coin->name,
                    $coin->symbol,
                    $coin->date_added,
                    $coin->max_supply,
                    $coin->circulating_supply,
                    $coin->total_supply,
                    $coin->last_updated,
                    $coin->quote->USD->price,
                    $coin->quote->USD->volume_24h,
                    $coin->quote->USD->volume_change_24h,
                    $coin->quote->USD->percent_change_24h,
                    $coin->quote->USD->market_cap,
                )
            );
        }
        return $coins;
    }

    public function getBySymbol(string $coinSymbol): ?Coin
    {
        $results = (new CoinMarketCapCryptoAPI())->getSingle($coinSymbol);
        if ($results === null) {
            return null;
        }
        $coin = $results->data->$coinSymbol[0];

        return new Coin(
            $coin->id,
            $coin->name,
            $coin->symbol,
            $coin->date_added,
            $coin->max_supply,
            $coin->circulating_supply,
            $coin->total_supply,
            $coin->last_updated,
            $coin->quote->USD->price,
            $coin->quote->USD->volume_24h,
            $coin->quote->USD->volume_change_24h,
            $coin->quote->USD->percent_change_24h,
            $coin->quote->USD->market_cap,
        );
    }
}