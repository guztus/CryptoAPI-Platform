<?php declare(strict_types=1);

namespace App\Services;

use App\Repositories\Coins\CoinsRepository;
use App\Repositories\Coins\CoinMarketCapCryptoCoinsRepository;
use App\Repositories\Coins\OtherServiceCryptoCoinsRepository;

class CoinsService
{
    private CoinsRepository $coinsRepository;

    public function __construct()
    {
//        $this->coinsRepository = new OtherServiceCryptoCoinsRepository();
        $this->coinsRepository = new CoinMarketCapCryptoCoinsRepository();
    }

    public function execute(?string $coinSymbol = null)
    {
        if ($coinSymbol) {
            return $this->coinsRepository->getBySymbol($coinSymbol);
        }
        return $this->coinsRepository->getList();
    }
}