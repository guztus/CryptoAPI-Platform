<?php

namespace App\Services;

use App\Models\Collections\CoinCollection;
use App\Repositories\CoinsRepository;
use App\Repositories\CryptoCoinsRepository;

class CoinsService
{
    private CoinsRepository $coinsRepository;

    public function __construct()
    {
        $this->coinsRepository = new CryptoCoinsRepository();
    }

    public function execute(): CoinCollection
    {
        return $this->coinsRepository->getList();
    }
}