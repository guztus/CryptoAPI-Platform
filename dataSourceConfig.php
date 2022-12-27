<?php

$sources = [
    'CoinMarketCap' => \App\Repositories\Coins\CoinMarketCapCryptoCoinsRepository::class,
    'LocalDatabase' => \App\Repositories\Coins\CryptoCoinTable::class
];

return [
    'source' => $sources['LocalDatabase'],
];