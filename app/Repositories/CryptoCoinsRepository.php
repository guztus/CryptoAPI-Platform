<?php

namespace App\Repositories;

use App\APIRequest;
use App\Models\Coin;
use App\Models\Collections\CoinCollection;

class CryptoCoinsRepository implements CoinsRepository
{
    public function getList(): CoinCollection
    {
        $results = (new APIRequest())->getResults();
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
}