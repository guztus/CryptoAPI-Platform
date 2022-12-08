<?php declare(strict_types=1);

namespace App\Services;

use App\Models\Collections\CoinCollection;
use App\Repositories\Coins\CoinsRepository;
use App\Repositories\Coins\CryptoCoinsRepository;

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