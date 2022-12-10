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
            $coins->addCoins(
                new Coin(
                    $coin->id,
                    $coin->name,
                    $coin->symbol,
                    $coin->date_added,
                    $coin->max_supply,
                    $coin->circulating_supply,
                    $coin->total_supply,
                    $coin->cmc_rank,
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

    public function getBySymbol(string $coinSymbol): Coin
    {
        $result = (new CoinMarketCapCryptoAPI())->getSingle($coinSymbol)->data->$coinSymbol[0];

        return new Coin(
            $result->id,
            $result->name,
            $result->symbol,
            $result->date_added,
            $result->max_supply,
            $result->circulating_supply,
            $result->total_supply,
            $result->cmc_rank,
            $result->last_updated,
            $result->quote->USD->price,
            $result->quote->USD->volume_24h,
            $result->quote->USD->volume_change_24h,
            $result->quote->USD->percent_change_24h,
            $result->quote->USD->market_cap,
        );
    }
}